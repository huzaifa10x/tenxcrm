<?php

// app/Http/Controllers/ProjectController.php

namespace App\Http\Controllers;

use App\Mail\ProjectCancellationRequestedMail;
use App\Models\CancellationReason;
use App\Models\ClientCredential;
use App\Models\Company;
use App\Models\Industry;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\ProjectRenewal;
use App\Models\Role;
use App\Models\SeoProject;
use App\Models\SocialMediaProject;
use App\Models\Task;
use App\Models\User;
use App\Models\WebDevProject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('projects-category');
        $accountManagerId = $request->query('account_manager_id');
        $salesManagerId = $request->query('sales_manager_id');
        $techManagerId = $request->query('tech_manager_id');
        $pStatus = $request->query('project_status');
        $user = auth()->user();

        $accountManagers = User::whereHas('roles', fn($q) => $q->where('name', 'AM'))->get();
        $salesManagers = User::whereHas('roles', fn($q) => $q->where('name', 'SALES MANAGER'))->get();
        $users = User::all();

        $query = Project::with(['salesManagers', 'accountManagers', 'techManagers', 'seoTechs', 'contentWriters', 'seoAdmins', 'latestRenewal']);

        $statusCounts = [];
        $typeCounts   = [];
        $extraData = [];

        if ($accountManagerId) {
            $query->whereHas('accountManagers', function ($q) use ($accountManagerId) {
                $q->where('user_id', $accountManagerId);
            });

            $statusCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
                $q->where('user_id', $accountManagerId);
            })
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $typeCounts = Project::whereHas('accountManagers', function ($q) use ($accountManagerId) {
                $q->where('user_id', $accountManagerId);
            })
                ->selectRaw('project_type, count(*) as count')
                ->groupBy('project_type')
                ->pluck('count', 'project_type');
            $projects = $query->paginate(10)->withQueryString();

            $projects = Project::whereHas('accountManagers', function ($query) use ($accountManagerId) {
                $query->where('user_id', $accountManagerId);
            })->get();

            $currentPortfolio = Project::whereHas('accountManagers', function ($query) use ($accountManagerId) {
                $query->where('user_id', $accountManagerId);
            })->where("status", "Current")
                ->selectRaw("SUM(contract_value / NULLIF(contract_term, 0)) as total")
                ->value("total");

            $newSell = Project::whereHas('salesManagers', function ($query) use ($accountManagerId) {
                $query->where('user_id', $accountManagerId);
            })
                ->where('status', 'Current')
                ->whereBetween('contract_start_date', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->selectRaw('SUM(contract_value / NULLIF(contract_term, 0)) as total')
                ->value('total') ?? 0;

            $extraData += [
                'currentPortfolio',
                'newSell',
            ];
        }
        if ($salesManagerId) {
            $query->whereHas('salesManagers', function ($q) use ($salesManagerId) {
                $q->where('user_id', $salesManagerId);
            });
            $projects = $query->paginate(10)->withQueryString();
        }
        if ($techManagerId) {
            $query->whereHas('techManagers', function ($q) use ($techManagerId) {
                $q->where('user_id', $techManagerId);
            });
            $projects = $query->paginate(10)->withQueryString();
        }
        if ($pStatus) {
            $query->where('status', $pStatus);
        }
        if (
            $category && in_array($category, ['SEO', 'Hosting']) &&
            ($user->hasRole('ADMIN') || $user->hasRole('SEO ADMIN'))
        ) {
            $projects = $query->where('project_type', $category)->paginate(10)->withQueryString();
        } else if ($user->hasPermission('can view all projects')) {
            $projects = $query->paginate(10)->withQueryString();
        } else {
            $projects = $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('salesManagers', fn($q) => $q->where('user_id', $user->id))
                    ->orWhereHas('accountManagers', fn($q) => $q->where('user_id', $user->id))
                    ->orWhereHas('collaborators', fn($q) => $q->where('user_id', $user->id))
                    ->orWhereHas('techManagers', fn($q) => $q->where('user_id', $user->id))
                    ->orWhereHas('seoAdmins', fn($q) => $q->where('user_id', $user->id));
            })->paginate(10)->withQueryString();
        }

        return view('projects.index', compact('projects', 'accountManagers', 'statusCounts', 'typeCounts', 'users', 'salesManagers', $extraData));
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'project_id'       => 'required|exists:projects,id',
            'reason'           => 'required|string',
            'effective_date'   => 'nullable|date',
            'additional_notes' => 'nullable|string|max:2000',
        ]);

        $cancellation = CancellationReason::create([
            'project_id' => $request->project_id,
            'reason'     => $request->reason,
            'approved'   => false,
        ]);

        $this->notifyAdminsOfCancellation($cancellation, $request->effective_date, $request->additional_notes);

        return response()->json(['message' => 'Cancellation submitted']);
    }

    private function notifyAdminsOfCancellation(CancellationReason $cancellation, ?string $effectiveDate, ?string $notes): void
    {
        $project   = Project::find($cancellation->project_id);
        $requester = auth()->user();

        if (!$project || !$requester) {
            return;
        }

        $admins = User::whereHas('roles', fn($q) => $q->where('name', 'ADMIN'))
            ->whereNotNull('email')
            ->where('email', '!=', 'system@10xdigital.ae')
            ->where('id', '!=', $requester->id)
            ->get();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(
                    new ProjectCancellationRequestedMail($project, $cancellation, $requester, $admin, $effectiveDate, $notes)
                );
            } catch (\Throwable $e) {
                Log::error('Cancellation email failed', ['admin' => $admin->email, 'error' => $e->getMessage()]);
            }
        }
    }

    public function approveCancellation($id)
    {
        $cancellation = CancellationReason::findOrFail($id);
        $project = $cancellation->project;

        DB::transaction(function () use ($cancellation, $project) {
            $cancellation->update(['approved' => true]);
            $project->update(['status' => 'Cancelled']);
            $project->tasks()->update(['status' => 'Blocked']);
        });

        return back()->with('success', 'Project cancelled and tasks blocked.');
    }
    public function create()
    {
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $companies = Company::orderBy('name', 'asc')->get();
        return view('projects.create', compact('industries', 'users', 'paymentMethods', 'companies'));
    }

    // public function renew(Request $request, $projectId)
    // {
    //     $project = Project::findOrFail($projectId);

    //     // Mark previous renewals as old
    //     $project->renewals()->update(['is_current' => false]);

    //     $data = $request->validate([
    //         'contract_value' => 'required|numeric',
    //         'contract_term' => 'required|integer',
    //         'free_months' => 'nullable|integer',
    //         'contract_start_date' => 'required|date',
    //         'payment_method_id' => 'required|exists:payment_methods,id',
    //         'handover_notes' => 'nullable|string',
    //         'contract_copy.*' => 'file',
    //         'sales_manager_ids' => 'array',
    //         'account_manager_ids' => 'array',
    //         'tech_manager_ids' => 'array',
    //         'seo_admin_ids' => 'array',
    //         'writer_ids' => 'array',
    //     ]);

    //     $renewal = ProjectRenewal::create([
    //         ...$data,
    //         'project_id' => $project->id,
    //         'created_by' => auth()->id(),
    //     ]);

    //     if ($request->hasFile('contract_copy')) {
    //         foreach ($request->file('contract_copy') as $file) {
    //             $renewal->files()->create([
    //                 'file_path' => $file->store('project_contracts', 'public'),
    //                 'file_name' => $file->getClientOriginalName(),
    //                 'file_type' => $file->getClientMimeType(),
    //             ]);
    //         }
    //     }

    //     foreach (['SALES MANAGER' => 'sales_manager_ids', 'AM' => 'account_manager_ids', 'TECH MANAGER' => 'tech_manager_ids', 'SEO ADMIN' => 'seo_admin_ids', 'CONTENT WRITE' => 'content_writers_ids'] as $role => $field) {
    //         foreach ($request->input($field, []) as $userId) {
    //             $renewal->assignments()->create([
    //                 'user_id' => $userId,
    //                 'role_type' => $role
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Project renewed successfully');
    // }

    public function renew(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        // Validate the incoming request
        $data = $request->validate([
            'contract_value' => 'required|numeric',
            'contract_term' => 'required|integer',
            'free_months' => 'nullable|integer',
            'contract_start_date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'handover_notes' => 'nullable|string',
            'contract_copy.*' => 'file',
            'sales_manager_ids' => 'array',
            'account_manager_ids' => 'array',
            'tech_manager_ids' => 'array',
            'seo_admin_ids' => 'array',
            'writer_ids' => 'array',
        ]);

        // ✅ Step 1: If this is the FIRST renewal, create an "Old Renewal" snapshot
        if ($project->renewals()->count() == 0) {
            $oldRenewal = ProjectRenewal::create([
                'project_id'          => $project->id,
                'contract_value'      => $project->contract_value,
                'contract_term'       => $project->contract_term,
                'free_months'         => $project->free_months ?? 0,
                'contract_start_date' => $project->contract_start_date,
                'payment_method_id'   => $project->payment_method_id,
                'handover_notes'      => $project->handover_notes ?? null,
                'created_by'          => $project->created_by ?? auth()->id(),
                'is_current'          => false, // Snapshot is old
            ]);

            // Copy Project Assignments to Old Renewal
            foreach (
                [
                    'SALES MANAGER' => $project->salesManagers->pluck('id'),
                    'AM'            => $project->accountManagers->pluck('id'),
                    'TECH MANAGER'  => $project->techManagers->pluck('id'),
                    'SEO ADMIN'     => $project->seoAdmins->pluck('id'),
                    'CONTENT WRITE' => $project->contentWriters->pluck('id'),
                ] as $role => $userIds
            ) {
                foreach ($userIds as $userId) {
                    $oldRenewal->assignments()->create([
                        'user_id'   => $userId,
                        'role_type' => $role,
                    ]);
                }
            }

            // Copy Project Contract Files to Old Renewal
            foreach ($project->contractFiles as $file) {
                $oldRenewal->files()->create([
                    'file_path' => $file->file_path,
                    'file_name' => $file->file_name,
                    'file_type' => $file->file_type,
                ]);
            }
        }

        // ✅ Step 2: Mark all old renewals as non-current
        $project->renewals()->update(['is_current' => false]);

        // ✅ Step 3: Create the new renewal
        $renewal = ProjectRenewal::create([
            ...$data,
            'project_id' => $project->id,
            'created_by' => auth()->id(),
            'is_current' => true,
        ]);

        // ✅ Step 4: Upload new files if any
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $renewal->files()->create([
                    'file_path' => $file->store('project_contracts', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        // ✅ Step 5: Assign new users for this renewal
        foreach (
            [
                'SALES MANAGER' => 'sales_manager_ids',
                'AM'            => 'account_manager_ids',
                'TECH MANAGER'  => 'tech_manager_ids',
                'SEO ADMIN'     => 'seo_admin_ids',
                'CONTENT WRITE' => 'writer_ids', // fixed your typo
            ] as $role => $field
        ) {
            foreach ($request->input($field, []) as $userId) {
                $renewal->assignments()->create([
                    'user_id'   => $userId,
                    'role_type' => $role
                ]);
            }
        }

        $project->status = 'Current';
        $project->save();

        return redirect()->back()->with('success', 'Project renewed successfully, with old snapshot saved!');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'status' => 'required',
            'industry_id' => 'required|exists:industries,id',
            'company_id' => 'required|exists:companies,id',
            'created_by' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_months' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'handover_notes' => 'nullable|string',
            'project_type' => 'required|in:SEO,Google Ads,Social Media,Web Development,Hosting,Other,Web Maintenance,AI',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $projectData = $validator->validated();
        $project = Project::create($projectData);

        $project->salesManagers()->sync($request->sales_manager_ids);
        $project->accountManagers()->sync($request->account_manager_ids);
        $project->techManagers()->sync($request->tech_manager_ids);
        $project->seoAdmins()->sync($request->seo_admin_ids);
        $project->contentWriters()->sync($request->content_writers_ids);

        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $path = $file->store('project_contracts', 'public');
                $project->contractFiles()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        if ($request->project_type == 'SEO') {
            $validator = Validator::make($request->all(), [
                'number_of_keywords' => 'required|integer',
                'milestone_1_percentage' => 'required|numeric',
                'milestone_1_date' => 'required|date',
                'milestone_2_percentage' => 'required|numeric',
                'milestone_2_date' => 'required|date',
                'milestone_3_percentage' => 'required|numeric',
                'milestone_3_date' => 'required|date',
            ]);

            SeoProject::create(array_merge(['project_id' => $project->id], $validator->validated()));

            $initialTasks = [
                'Handover Meet with The Sales Manager',
                'Intro Call',
                'First Client Meeting',
                'Onboarding Email',
                'Upload the Content Intake Form',
                'Upload the Local Listing Form',
                'Add the Client Credentials',
                'Connect the GA, GSC, GTM, and GMB'
            ];

            foreach ($initialTasks as $taskTitle) {
                Task::create([
                    'is_automated' => 1,
                    'title' => $taskTitle,
                    'description' => null,
                    'project_id' => $project->id,
                    'department_id' => null,
                    'created_by' => User::where('email', 'system@10xdigital.ae')->value('id') ?? 1,
                    'assigned_to' => $request->account_manager_ids[0],
                    'priority' => 'Medium',
                    'type' => 'AM',
                    'status' => 'Todo',
                    'due_date' => Carbon::now()->addDays(7),
                    'start_date' => Carbon::now(),
                    'repeat_task' => 0,
                ]);
            }
        } elseif ($request->project_type == 'Social Media') {
            $validator = Validator::make($request->all(), [
                'channels' => 'nullable|array',
                'channels.*' => 'string|in:Facebook,Instagram,LinkedIn,Twitter,YouTube,TikTok,Snapchat,Pinterest,Google My Business,Other',
                'video_shoots_per_month' => 'required|integer',
                'static_posts' => 'required|integer',
                'reels' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            $data = $validator->validated();
            $data['channels'] = json_encode($data['channels']);
            SocialMediaProject::create(array_merge(['project_id' => $project->id], $data));
        } elseif ($request->project_type == 'Web Development') {
            $validator = Validator::make($request->all(), [
                'payment_type' => 'required|in:Partial,Full',
                'remaining_payment' => 'required|numeric',
                'site_condition' => 'required|in:New Project,Maintenance Project',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            WebDevProject::create(array_merge(['project_id' => $project->id], $validator->validated()));
        } else {
        }
        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $currentRenewal = $project->renewals()->where('is_current', true)->latest()->first();
        if (!$currentRenewal) {
            $currentRenewal = $project;
        }
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $departments = Role::all();
        $taskCounts = Task::selectRaw('project_id, type, COUNT(*) as count')
            ->where('project_id', $project->id)->whereIn('status', ['Todo', 'In Progress', 'Review'])
            ->groupBy('project_id', 'type')
            ->pluck('count', 'type');

        $regularTasks = $project->tasks()->where('is_automated', 0)->where('repeat_task', 0)->get();
        $sequenceTasks = $project->tasks()->where('is_automated', 1)->get();
        $completedTasks = $project->tasks()->where('status', 'Completed')->get();
        $repeatingTasks = Task::where('project_id', $id)
            ->where(function ($query) {
                $query->whereIn('id', function ($subQuery) {
                    $subQuery->select('task_id')->from('repeating_tasks');
                })->orWhere('repeat_task', 1);
            })
            ->get();
        $contractStartDate = Carbon::parse($currentRenewal->contract_start_date);
        $contractEndDate = $contractStartDate->copy()->addMonths($currentRenewal->contract_term);
        $monthlyInvoice = ($currentRenewal->contract_term > 0) ? round($currentRenewal->contract_value / $currentRenewal->contract_term, 2) : 0;
        $nextInvoiceDate = $contractStartDate->copy()->addMonth();

        $sequenceTasksFiles = [];
        foreach ($sequenceTasks as $task) {
            foreach ($task->completions as $completion) {
                if (Str::startsWith($completion->field_value, 'task_attachments/')) {
                    $sequenceTasksFiles[] = [
                        'name' => "#" . $task->id . " - " . str_replace('_', ' ', ucfirst($completion->field_name)),
                        'file' => $completion->field_value
                    ];
                }
            }
        }
        return view('projects.show', compact(
            'project',
            'industries',
            'users',
            'paymentMethods',
            'departments',
            'taskCounts',
            'regularTasks',
            'sequenceTasks',
            'completedTasks',
            'repeatingTasks',
            'currentRenewal',
            'contractStartDate',
            'contractEndDate',
            'monthlyInvoice',
            'nextInvoiceDate',
            'sequenceTasksFiles',
        ));
    }
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $companies = Company::all();
        return view('projects.edit', compact('project', 'industries', 'users', 'paymentMethods', 'companies'));
    }
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'status' => 'required',
            'industry_id' => 'required|exists:industries,id',
            'company_id' => 'required|exists:companies,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_months' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'handover_notes' => 'nullable|string',
            'project_type' => 'required|in:SEO,Google Ads,Social Media,Web Development,Hosting,Other,Web Maintenance,AI',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $projectData = $validator->validated();
        $project->update($projectData);

        $project->salesManagers()->sync($request->sales_manager_ids);
        $project->accountManagers()->sync($request->account_manager_ids);
        $project->techManagers()->sync($request->tech_manager_ids);

        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $path = $file->store('project_contracts', 'public');
                $project->contractFiles()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        if ($request->project_type == 'SEO') {
            $validator = Validator::make($request->all(), [
                'number_of_keywords' => 'required|integer',
                'milestone_1_percentage' => 'required|numeric',
                'milestone_1_date' => 'required|date',
                'milestone_2_percentage' => 'required|numeric',
                'milestone_2_date' => 'required|date',
                'milestone_3_percentage' => 'required|numeric',
                'milestone_3_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $seoProject = SeoProject::where('project_id', $project->id)->first();
            if ($seoProject) {
                $seoProject->update($validator->validated());
            } else {
                SeoProject::create(array_merge(['project_id' => $project->id], $validator->validated()));
            }
        } elseif ($request->project_type == 'Social Media') {
            $validator = Validator::make($request->all(), [
                'channels' => 'required|array',
                'channels.*' => 'string|in:Facebook,Instagram,LinkedIn,Twitter,YouTube,TikTok,Snapchat,Pinterest,Google My Business,Other',
                'video_shoots_per_month' => 'required|integer',
                'static_posts' => 'required|integer',
                'reels' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            $data = $validator->validated();
            $data['channels'] = json_encode($data['channels']);
            $socialMediaProject = SocialMediaProject::where('project_id', $project->id)->first();
            if ($socialMediaProject) {
                $socialMediaProject->update($data);
            } else {
                SocialMediaProject::create(array_merge(['project_id' => $project->id], $data));
            }
        } elseif ($request->project_type == 'Web Development') {
            $validator = Validator::make($request->all(), [
                'payment_type' => 'required|in:Partial,Full',
                'remaining_payment' => 'required|numeric',
                'site_condition' => 'required|in:New Project,Maintenance Project',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $webDevProject = WebDevProject::where('project_id', $project->id)->first();
            if ($webDevProject) {
                $webDevProject->update($validator->validated());
            } else {
                WebDevProject::create(array_merge(['project_id' => $project->id], $validator->validated()));
            }
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        foreach ($project->contractFiles as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }
        if ($project->project_type == 'SEO') {
            SeoProject::where('project_id', $project->id)->delete();
        } elseif ($project->project_type == 'Social Media') {
            SocialMediaProject::where('project_id', $project->id)->delete();
        } elseif ($project->project_type == 'Web Development') {
            WebDevProject::where('project_id', $project->id)->delete();
        }
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
    public function uploadProjectFiles(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validator = Validator::make($request->all(), [
            'contract_copy' => 'required|array',
            'contract_copy.*' => 'file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->file('contract_copy') as $file) {
            $path = $file->store('project_contracts', 'public');
            $project->contractFiles()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully!'
        ]);
    }
    public function deleteProjectFile($projectId, $fileId)
    {
        $project = Project::findOrFail($projectId);
        $file = $project->contractFiles()->findOrFail($fileId);

        // File ko storage se delete karo
        Storage::disk('public')->delete($file->file_path);

        // DB se bhi delete karo
        $file->delete();

        return response()->json(['success' => true, 'message' => 'File deleted successfully!']);
    }
    public function deleteAllProjectFiles($projectId)
    {
        $project = Project::findOrFail($projectId);

        if ($project->contractFiles()->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No files found to delete.'
            ]);
        }

        // Delete files from storage
        foreach ($project->contractFiles as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'All attachments deleted successfully.'
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->status = $request->status;
        $project->save();

        return response()->json(['success' => true, 'message' => 'Project status updated successfully!']);
    }
    public function getExtraFields(Request $request)
    {
        $projectType = $request->project_type;
        $fields = '';

        switch ($projectType) {
            case 'SEO':
                $fields .= '
                <div class="col-lg-6">
                    <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                    <input required class="form-control" type="number" id="number_of_keywords" name="number_of_keywords">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_1_percentage">Guarantee for 1st Milestone %</label>
                    <input required class="form-control" type="number" id="milestone_1_percentage" name="milestone_1_percentage">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_1_date">Guarantee for 1st Milestone Date</label>
                    <input required class="form-control date-picker" type="date" id="milestone_1_date" name="milestone_1_date">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone %</label>
                    <input required class="form-control" type="number" id="milestone_2_percentage" name="milestone_2_percentage">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone Date</label>
                    <input required class="form-control date-picker" type="date" id="milestone_2_date" name="milestone_2_date">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone %</label>
                    <input required class="form-control" type="number" id="milestone_3_percentage" name="milestone_3_percentage">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone Date</label>
                    <input required class="form-control date-picker" type="date" id="milestone_3_date" name="milestone_3_date">
                </div>
                ';
                break;

            case 'Social Media':
                $fields .= '
                <div class="col-lg-6">
                    <label class="form-label" for="channels">Channels</label>
                    <select class="form-control" id="channels" name="channels[]" multiple required>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="Twitter">Twitter</option>
                        <option value="YouTube">YouTube</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Snapchat">Snapchat</option>
                        <option value="Pinterest">Pinterest</option>
                        <option value="Google My Business">Google My Business</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="video_shoots_per_month">Video shoots Per Month</label>
                    <input class="form-control" type="number" id="video_shoots_per_month" name="video_shoots_per_month">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="static_posts">Static Posts Per Month</label>
                    <input class="form-control" type="number" id="static_posts" name="static_posts">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="reels">Reels Per Month</label>
                    <input class="form-control" type="text" id="reels" name="reels">
                </div>
                ';
                break;

            case 'Web Development':
                $fields .= '
                <div class="col-lg-6">
                    <label class="form-label" for="payment_type">Payment Type</label>
                    <select class="form-control" id="payment_type" name="payment_type" required>
                        <option value="Partial">Partial</option>
                        <option value="Full">Full</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="remaining_payment">Remaining Payment</label>
                    <input class="form-control" type="number" id="remaining_payment" name="remaining_payment">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="site_condition">Site Condition</label>
                    <select class="form-control" id="site_condition" name="site_condition" required>
                        <option value="New Project">New Project</option>
                        <option value="Maintenance Project">Maintenance Project</option>
                    </select>
                </div>
                ';
                break;

            // Add cases for other project types as needed

            default:
                $fields = ''; // No extra fields for other project types
        }

        return response()->json($fields); // Return fields as JSON response
    }
    public function storeClientCredentials(Request $request, Project $project)
    {
        $request->validate([
            'credentials' => 'nullable|array',
            'credentials.*.url' => 'nullable|string',
            'credentials.*.platform_name' => 'nullable|string',
            'credentials.*.username' => 'nullable|string',
            'credentials.*.password' => 'nullable|string',
        ]);

        foreach ($request->credentials as $cred) {
            ClientCredential::create([
                'project_id' => $project->id,
                'url' => $cred['url'] ?? "",
                'platform_name' => $cred['platform_name'] ?? "",
                'username' => $cred['username'] ?? "",
                'password' => $cred['password'] ?? "",
            ]);
        }

        return response()->json(['message' => 'Client credentials saved successfully.']);
    }
    public function deleteClientCredential(Request $request, Project $project)
    {
        $request->validate([
            'id' => 'required|integer|exists:client_credentials,id',
        ]);

        $credential = ClientCredential::where('id', $request->id)
            ->where('project_id', $project->id)
            ->first();

        if (!$credential) {
            return response()->json(['message' => 'Credential not found'], 404);
        }

        $credential->delete();

        return response()->json(['message' => 'Credential deleted successfully']);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $projects = Project::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($projects);
    }
}
