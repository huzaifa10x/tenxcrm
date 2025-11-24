<?php

namespace App\Http\Controllers;

use App\Models\WebsiteProject;
use App\Models\Project;
use App\Models\User;
use App\Models\WebsiteProjectChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WebsiteProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = WebsiteProject::with(['project', 'developer', 'leadDeveloper']);

        // Filters
        if ($request->platform) {
            $query->platform($request->platform);
        }

        if ($request->design_status) {
            $query->designStatus($request->design_status);
        }

        if ($request->development_status) {
            $query->developmentStatus($request->development_status);
        }

        if ($request->developer_id) {
            $query->assignedToDeveloper($request->developer_id);
        }

        if ($request->ready_for_go_live) {
            $query->readyForGoLive();
        }

        if ($request->overdue) {
            $query->where('target_go_live_date', '<', now())
                  ->whereNull('go_live_date');
        }

        $websiteProjects = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total_projects' => WebsiteProject::count(),
            'in_design' => WebsiteProject::whereIn('design_status', ['In Progress', 'Completed'])->count(),
            'in_development' => WebsiteProject::whereIn('development_status', ['In Progress', 'Internal QA', 'Client Review'])->count(),
            'awaiting_approval' => WebsiteProject::where('development_status', 'Completed')->count(),
            'ready_for_go_live' => WebsiteProject::readyForGoLive()->count(),
            'delivered' => WebsiteProject::where('development_status', 'Delivered')->count(),
        ];

        $developers = User::whereHas('roles', function($q) {
            $q->where('name', 'DEVELOPMENT');
        })->get();

        return view('website-projects.index', compact('websiteProjects', 'stats', 'developers'));
    }

    public function create()
    {
        $projects = Project::get();

        $developers = User::whereHas('roles', function($q) {
            $q->where('name', 'DEVELOPMENT');
        })->get();

        $leadDevelopers = User::where('name', 'LIKE', '%Huzaifa%')
                              ->orWhereHas('roles', function($q) {
                                  $q->where('name', 'ADMIN');
                              })->get();

        return view('website-projects.create', compact('projects', 'developers', 'leadDevelopers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|unique:website_projects,project_id',
            'platform' => 'required|in:WordPress,Next.js',
            'developer_assigned' => 'nullable|exists:users,id',
            'lead_developer' => 'nullable|exists:users,id',
            'has_current_seo' => 'boolean',
            'has_current_google_ads' => 'boolean',
            'target_go_live_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Auto-assign developer based on platform
        if (!$request->developer_assigned) {
            $defaultDeveloper = User::where('name', 'LIKE', $request->platform === 'WordPress' ? '%Fabiha%' : '%Daniyal%')->first();
            $validated['developer_assigned'] = $defaultDeveloper?->id;
        }

        // Auto-assign lead developer
        if (!$request->lead_developer) {
            $leadDev = User::where('name', 'LIKE', '%Huzaifa%')->first();
            $validated['lead_developer'] = $leadDev?->id;
        }

        $websiteProject = WebsiteProject::create($validated);

        $websiteProject->logHistory('Website project created');

        // Notify developer and lead developer
        if ($websiteProject->developer) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\CustomNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $websiteProject->developer->id,
                'data' => json_encode([
                    'title' => 'New Website Project Assigned',
                    'message' => "Website project '{$websiteProject->project->title}' has been assigned to you.",
                    'url' => route('website-projects.show', $websiteProject->id),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('website-projects.show', $websiteProject->id)
                        ->with('success', 'Website project created successfully');
    }

    public function show(WebsiteProject $websiteProject)
    {
        $websiteProject->load([
            'project',
            'developer',
            'leadDeveloper',
            'checklists',
            'history.user',
            'leadDevQAApprovedBy',
            'amValidationApprovedBy',
            'seoTechApprovedBy',
            'adsAmApprovedBy'
        ]);

        $developers = User::whereHas('roles', function($q) {
            $q->where('name', 'DEVELOPMENT');
        })->get();

        return view('website-projects.show', compact('websiteProject', 'developers'));
    }

    public function updateDesignStatus(Request $request, WebsiteProject $websiteProject)
    {
        $validated = $request->validate([
            'design_status' => 'required|in:Not Started,In Progress,Completed,Approved',
            'figma_link' => 'nullable|url',
            'design_approval_screenshot' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $additionalData = [];

        if ($request->hasFile('design_approval_screenshot')) {
            $path = $request->file('design_approval_screenshot')->store('design_approvals', 'public');
            $additionalData['design_approval_screenshot'] = $path;
        }

        if ($request->figma_link) {
            $additionalData['figma_link'] = $request->figma_link;
        }

        $websiteProject->updateDesignStatus($validated['design_status'], $additionalData);

        return back()->with('success', 'Design status updated successfully');
    }

    public function updateDevelopmentStatus(Request $request, WebsiteProject $websiteProject)
    {
        $validated = $request->validate([
            'development_status' => 'required|in:Not Started,In Progress,Internal QA,Client Review,Completed,Delivered',
            'staging_link' => 'nullable|url',
            'completion_agreement' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $additionalData = [];

        if ($request->hasFile('completion_agreement')) {
            $path = $request->file('completion_agreement')->store('completion_agreements', 'public');
            $additionalData['completion_agreement'] = $path;
        }

        if ($request->staging_link) {
            $additionalData['staging_link'] = $request->staging_link;
        }

        $websiteProject->updateDevelopmentStatus($validated['development_status'], $additionalData);

        return back()->with('success', 'Development status updated successfully');
    }

    public function submitChecklist(Request $request, WebsiteProject $websiteProject)
    {
        $type = $request->type;

        $checklist = WebsiteProjectChecklist::updateOrCreate(
            [
                'website_project_id' => $websiteProject->id,
                'type' => $type,
            ],
            array_merge($request->except(['type', '_token']), [
                'checked_by' => auth()->id(),
                'checked_at' => now(),
            ])
        );

        // If checklist is complete, approve the corresponding section
        if ($checklist->isComplete()) {
            switch ($type) {
                case 'lead_dev_qa':
                    $websiteProject->approveLeadDevQA(auth()->id());
                    break;
                case 'am_validation':
                    $websiteProject->approveAMValidation(auth()->id());
                    break;
                case 'seo_tech':
                    $websiteProject->approveSEOTech(auth()->id());
                    break;
                case 'ads_am':
                    $websiteProject->approveAdsAM(auth()->id());
                    break;
            }
        }

        return back()->with('success', 'Checklist updated successfully');
    }

    public function markAsLive(WebsiteProject $websiteProject)
    {
        if (!$websiteProject->approved_for_go_live) {
            return back()->with('error', 'Project is not approved for go-live yet');
        }

        $websiteProject->markAsLive();

        // Notify all stakeholders
        $users = collect([
            $websiteProject->project->accountManagers->first(),
            $websiteProject->leadDeveloper,
            $websiteProject->developer,
            $websiteProject->project->seoAdmins->first()
        ])->filter()->unique('id');

        foreach ($users as $user) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\CustomNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Website Project Went Live',
                    'message' => "Website project '{$websiteProject->project->title}' is now live!",
                    'url' => route('website-projects.show', $websiteProject->id),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Project marked as live successfully');
    }

    public function update(Request $request, WebsiteProject $websiteProject)
    {
        $validated = $request->validate([
            'platform' => 'nullable|in:WordPress,Next.js',
            'developer_assigned' => 'nullable|exists:users,id',
            'lead_developer' => 'nullable|exists:users,id',
            'has_current_seo' => 'boolean',
            'has_current_google_ads' => 'boolean',
            'target_go_live_date' => 'nullable|date',
            'staging_link' => 'nullable|url',
            'figma_link' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        $websiteProject->update($validated);

        $websiteProject->logHistory('Website project details updated');

        return back()->with('success', 'Website project updated successfully');
    }

    public function performanceReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $developers = User::whereHas('roles', function($q) {
            $q->where('name', 'DEVELOPMENT');
        })->get();

        $performanceData = [];

        foreach ($developers as $developer) {
            $delivered = WebsiteProject::where('developer_assigned', $developer->id)
                ->whereBetween('go_live_date', [$startDate, $endDate])
                ->count();

            $inProgress = WebsiteProject::where('developer_assigned', $developer->id)
                ->whereIn('development_status', ['In Progress', 'Internal QA', 'Client Review'])
                ->count();

            $performanceData[] = [
                'developer' => $developer,
                'delivered' => $delivered,
                'in_progress' => $inProgress,
            ];
        }

        $overallStats = [
            'in_design' => WebsiteProject::whereIn('design_status', ['In Progress', 'Completed'])->count(),
            'in_development' => WebsiteProject::whereIn('development_status', ['In Progress', 'Internal QA', 'Client Review'])->count(),
            'awaiting_acceptance' => WebsiteProject::where('development_status', 'Completed')->count(),
            'total_delivered' => WebsiteProject::whereBetween('go_live_date', [$startDate, $endDate])->count(),
        ];

        return view('website-projects.performance', compact('performanceData', 'overallStats', 'startDate', 'endDate'));
    }

    public function destroy(WebsiteProject $websiteProject)
    {
        $websiteProject->delete();
        return redirect()->route('website-projects.index')->with('success', 'Website project deleted successfully');
    }
}
