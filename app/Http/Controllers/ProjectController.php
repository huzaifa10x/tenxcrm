<?php

// app/Http/Controllers/ProjectController.php

namespace App\Http\Controllers;

use App\Models\ClientCredential;
use App\Models\Company;
use App\Models\Industry;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Role;
use App\Models\SeoProject;
use App\Models\SocialMediaProject;
use App\Models\Task;
use App\Models\User;
use App\Models\WebDevProject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $user = auth()->user();
        if ($user->hasPermission('can view all projects')) {
            $projects = Project::with(['salesManagers', 'accountManagers', 'techManagers', 'seoTechs', 'contentWriters', 'seoAdmins'])
                ->paginate(10);
        } else {
            $projects = Project::with(['salesManagers', 'accountManagers', 'techManagers'])
                ->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhereHas('salesManagers', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })
                        ->orWhereHas('accountManagers', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })
                        ->orWhereHas('collaborators', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->orWhereHas('techManagers', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->paginate(10);
        }
        return view('projects.index', compact('projects'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $companies = Company::all();
        return view('projects.create', compact('industries', 'users', 'paymentMethods', 'companies'));
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
            'contract_copy.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'handover_notes' => 'nullable|string',
            'project_type' => 'required|in:SEO,Google Ads,Social Media,Web Development,Hosting,Other',
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
                    'created_by' => auth()->id(),
                    'assigned_to' => $request->account_manager_ids[0],
                    'priority' => 'Medium',
                    'type' => 'SEO ADMIN',
                    'status' => 'Todo',
                    'due_date' => Carbon::now()->addDays(7),
                    'start_date' => Carbon::now(),
                    'repeat_task' => false,
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

    // Display the specified resource.
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $departments = Role::all();
        $taskCounts = Task::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $regularTasks = $project->tasks()->where('is_automated', 0)->where('repeat_task', 0)->get();
        $sequenceTasks = $project->tasks()->where('is_automated', 1)->get();
        $repeatingTasks = Task::where('project_id', $id)
            ->where(function ($query) {
                $query->whereIn('id', function ($subQuery) {
                    $subQuery->select('task_id')->from('repeating_tasks');
                })->orWhere('repeat_task', 1);
            })
            ->get();
        $contractStartDate = Carbon::parse($project->contract_start_date);
        $contractEndDate = $contractStartDate->copy()->addMonths($project->contract_term);
        $monthlyInvoice = ($project->contract_term > 0) ? round($project->contract_value / $project->contract_term, 2) : 0;
        $nextInvoiceDate = $contractStartDate->copy()->addMonth();

        return view('projects.show', compact(
            'project',
            'industries',
            'users',
            'paymentMethods',
            'departments',
            'taskCounts',
            'regularTasks',
            'sequenceTasks',
            'repeatingTasks',
            'contractStartDate',
            'contractEndDate',
            'monthlyInvoice',
            'nextInvoiceDate',
        ));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $industries = Industry::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        $companies = Company::all();
        return view('projects.edit', compact('project', 'industries', 'users', 'paymentMethods', 'companies'));
    }

    // Update the specified resource in storage.
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
            'contract_copy.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'handover_notes' => 'nullable|string',
            'project_type' => 'required|in:SEO,Google Ads,Social Media,Web Development,Hosting,Other',
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

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Delete related contract files
        foreach ($project->contractFiles as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        // Delete related project type specific data
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
            'contract_copy.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
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
                    <input required class="form-control" type="date" id="milestone_1_date" name="milestone_1_date">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone %</label>
                    <input required class="form-control" type="number" id="milestone_2_percentage" name="milestone_2_percentage">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone Date</label>
                    <input required class="form-control" type="date" id="milestone_2_date" name="milestone_2_date">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone %</label>
                    <input required class="form-control" type="number" id="milestone_3_percentage" name="milestone_3_percentage">
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone Date</label>
                    <input required class="form-control" type="date" id="milestone_3_date" name="milestone_3_date">
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
            'credentials' => 'required|array',
            'credentials.*.url' => 'required|url',
            'credentials.*.platform_name' => 'required|string',
            'credentials.*.username' => 'required|string',
            'credentials.*.password' => 'required|string',
        ]);

        foreach ($request->credentials as $cred) {
            ClientCredential::create([
                'project_id' => $project->id,
                'url' => $cred['url'],
                'platform_name' => $cred['platform_name'],
                'username' => $cred['username'],
                'password' => $cred['password'], // Encrypt for security
            ]);
        }

        return response()->json(['message' => 'Client credentials saved successfully.']);
    }
}
