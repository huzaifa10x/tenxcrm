<?php

use App\Http\Controllers\Api\ClientLeadController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceNoteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDetailsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\ImageCompressorController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OffsiteTaskController;
use App\Http\Controllers\PartnerLinkController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectCollaboratorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectKeywordController;
use App\Http\Controllers\ProjectNoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskCollaboratorController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteProjectController;
use App\Jobs\CreateRecurringTaskJob;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::post('/groq-chat', [DashboardController::class, 'chat'])->name('groq.chat');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread.count');
Route::get('/compress', [ImageCompressorController::class, 'showForm'])->name('compress.form');
Route::post('/compress', [ImageCompressorController::class, 'compress'])->name('compress.run');
Route::get('/invoices/{uuid}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');
Route::get('email/check/index', [InvoiceController::class, 'emailCheckView'])->name('email.check.index');

Route::middleware('auth')->group(function () {
    Route::put('/admin/cancellations/{id}/approve', [ProjectController::class, 'approveCancellation'])->name('admin.approveCancellation');
    Route::post('/projects/cancel', [ProjectController::class, 'cancel'])->name('projects.cancel');
    Route::post('/todo/store', [TodoController::class, 'store'])->name('todo.store');
    Route::get('/todo/get-by-date', [TodoController::class, 'getTodosByDate'])->name('todo.getByDate');
    Route::post('/todo/mark-done', [TodoController::class, 'markAsDone'])->name('todo.markDone');
    Route::get('/todo/dates', [TodoController::class, 'getTodoDates'])->name('todo.getDates');
    Route::post('/punch-in', [AttendanceController::class, 'punchIn'])->name('attendance.punchIn');
    Route::post('/punch-out', [AttendanceController::class, 'punchOut'])->name('attendance.punchOut');
    Route::get('/settings', [GeneralSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [GeneralSettingController::class, 'update'])->name('settings.update');
    Route::post('/invoices/update-paid-status', [InvoiceController::class, 'updatePaidStatus'])->name('invoices.updatePaidStatus');
});

Route::get('/invoices/view/{uuid}', [InvoiceController::class, 'showPublic'])->name('invoices.public');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('website-projects')->name('website-projects.')->group(function () {
        Route::get('/', [WebsiteProjectController::class, 'index'])->name('index');
        Route::get('/create', [WebsiteProjectController::class, 'create'])->name('create');
        Route::post('/', [WebsiteProjectController::class, 'store'])->name('store');
        Route::get('/{websiteProject}', [WebsiteProjectController::class, 'show'])->name('show');
        Route::put('/{websiteProject}', [WebsiteProjectController::class, 'update'])->name('update');
        Route::delete('/{websiteProject}', [WebsiteProjectController::class, 'destroy'])->name('destroy');

        // Status Updates
        Route::put('/{websiteProject}/design-status', [WebsiteProjectController::class, 'updateDesignStatus'])->name('update-design-status');
        Route::put('/{websiteProject}/development-status', [WebsiteProjectController::class, 'updateDevelopmentStatus'])->name('update-development-status');

        // Checklist
        Route::post('/{websiteProject}/checklist', [WebsiteProjectController::class, 'submitChecklist'])->name('submit-checklist');

        // Go Live
        Route::put('/{websiteProject}/mark-as-live', [WebsiteProjectController::class, 'markAsLive'])->name('mark-as-live');

        // Performance Report
        Route::get('/reports/performance', [WebsiteProjectController::class, 'performanceReport'])->name('performance-report');
    });

    Route::post('/invoices/{id}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.sendEmail');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/get', [ServiceController::class, 'getServices'])->name('services.getServices');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

    Route::get('/leads/index', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/list', [LeadController::class, 'list'])->name('leads.list');
    Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
    Route::resource('lead-statuses', LeadStatusController::class)->only(['index', 'store']);
    Route::put('/lead-statuses/{id}', [LeadStatusController::class, 'update'])->name('lead-statuses.update');

    Route::get('leads/{lead}/comments', [LeadController::class, 'getComments'])->name('leads.comments.index');
    Route::post('leads/{lead}/comments', [LeadController::class, 'storeComment'])->name('leads.comments.store');
    Route::put('leads/{lead}/comments/{comment}', [LeadController::class, 'updateComment'])->name('leads.comments.update');
    Route::delete('leads/{lead}/comments/{comment}', [LeadController::class, 'deleteComment'])->name('leads.comments.destroy');

    Route::prefix('lead-sources')->controller(LeadSourceController::class)->group(function () {
        Route::get('/', 'index')->name('lead_sources.index');
        Route::get('/list', 'getSources')->name('lead_sources.getSources');
        Route::post('/', 'store')->name('lead_sources.store');
        Route::get('/{leadSource}', 'show');
        Route::put('/{leadSource}', 'update');
        Route::delete('/{leadSource}', 'destroy');
    });

    Route::get('/leads-view', [ClientLeadController::class, 'index'])->name('leads.view');
    Route::get('/leads/by-site/{website_name}', [ClientLeadController::class, 'getLeadsByWebsite'])
        ->name('leads.bySite');

    // Attendance Notes Routes
    Route::post('/attendance/{attendanceId}/notes', [AttendanceNoteController::class, 'store'])->name('attendance.notes.store');
    Route::get('/attendance/{attendanceId}/notes', [AttendanceNoteController::class, 'index'])->name('attendance.notes.index');

    Route::get('/search-projects', [ProjectController::class, 'search'])->name('projects.search');
    Route::get('/search-company', [CompanyController::class, 'search'])->name('company.search');
    Route::delete('/messages/delete/me/{id}', [MessageController::class, 'deleteForMe'])->name('messages.delete.me');
    Route::delete('/messages/delete/everyone/{id}', [MessageController::class, 'deleteForEveryone'])->name('messages.delete.everyone');

    Route::get('/users/unread', [MessageController::class, 'getUsersWithUnreadMessages'])->name('users.getUnreadMessages');

    Route::post('/messages/markAsRead/{userId}', [MessageController::class, 'markMessagesAsRead'])->name('messages.markAsRead');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/fetch/{userId}', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');

    Route::get('/project-notes/{projectId}', [ProjectNoteController::class, 'fetch'])->name('project-notes.fetch');
    Route::post('/project-notes', [ProjectNoteController::class, 'store'])->name('project-notes.store');
    Route::delete('/project-notes/{note}', [ProjectNoteController::class, 'destroy'])->name('project-notes.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account-managers', [UserController::class, 'showTeam'])->name('account-maanger.index');
    Route::get('/account-managers/users', [UserController::class, 'fetchAMUsers'])->name('account-maanger.users');
    Route::post('/account-managers/add-member', [UserController::class, 'addTeamMember'])->name('account-maanger.member');
    Route::delete('/account-manager/remove-member', [UserController::class, 'removeMember'])->name('account-maanger.remove-member');


    Route::get('/admin/attendance/filter', [DashboardController::class, 'filterAttendance'])->name('admin.attendance.filter');
    Route::get('/admin/attendance', [AttendanceController::class, 'monthlyAttendance'])->name('admin.attendance');
    Route::get('/admin/attendance/monthly', [AttendanceController::class, 'monthlyAttendance'])->name('attendance.monthly');
    Route::get('/dashboard/task-counts', [DashboardController::class, 'getTaskCounts'])->name('dashboard.taskCounts');
    Route::middleware('permission:can view users')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users-grid-view', [UserController::class, 'gridView'])->name('users.gridView');
        Route::post('/users/{id}/roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
    });
    Route::middleware('permission:can view companies')->group(function () {
        Route::resource('companies', CompanyController::class);

        Route::get('/company/{company}/details', [CompanyDetailsController::class, 'index'])->name('company.details.index');
        Route::post('/company/details/store', [CompanyDetailsController::class, 'store'])->name('company.details.store');
        Route::post('/company/details/update/{id}', [CompanyDetailsController::class, 'update'])->name('company.details.update');
        Route::delete('/company/details/destroy/{id}', [CompanyDetailsController::class, 'destroy'])->name('company.details.destroy');
    });
    Route::middleware('permission:can view roles')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::post('/roles/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
    });
    Route::middleware('permission:can view permissions')->group(function () {
        Route::resource('permissions', PermissionController::class);
    });
    Route::middleware('permission:can view industries')->group(function () {
        Route::resource('industries', IndustryController::class);
    });
    Route::middleware('permission:can view payment methods')->group(function () {
        Route::resource('payment-methods', PaymentMethodController::class);
    });
    // Route::middleware(['permission:can view all projects'])->group(function () {
    Route::resource('projects', ProjectController::class);
    // });
    Route::middleware('permission:can add project files')->group(function () {
        Route::post('/projects/{project}/files', [ProjectController::class, 'uploadProjectFiles'])->name('projects.uploadFiles');
    });

    Route::middleware('permission:can update project status')->group(function () {
        Route::post('/projects/{id}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    });
    Route::middleware('permission:can remove project files')->group(function () {
        Route::delete('/projects/{project}/file/{file}', [ProjectController::class, 'deleteProjectFile'])->name('projects.deleteFile');
        Route::delete('/projects/{project}/files', [ProjectController::class, 'deleteAllProjectFiles'])->name('projects.deleteAllFiles');
    });
    Route::get('projects-get-extra-fields', [ProjectController::class, 'getExtraFields'])->name('projects-get-extra-fields');
    Route::get('/users-by-department/{department}', [UserController::class, 'getUsersByDepartment'])->name('users.by.department');

    Route::get('/projects/{project}/collaborators', [ProjectCollaboratorController::class, 'index'])->name('project.collaborators');
    Route::post('/projects/collaborators', [ProjectCollaboratorController::class, 'store'])->name('project.collaborators.store');
    Route::delete('/projects/collaborators/{collaborator}', [ProjectCollaboratorController::class, 'destroy'])->name('project.collaborators.destroy');

    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/search/index', [TaskController::class, 'search'])->name('tasks.search.index');
    Route::get('/tasks/{task}/attachments', [TaskController::class, 'jsonTaskAttachments'])->name('tasks.attachments.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    Route::get('/tasks/clear-session', function () {
        session()->forget('open_task_id');
        return response()->json(['status' => 'cleared']);
    })->name('tasks.clearSession');

    Route::middleware('permission:can add task files')->group(function () {
        Route::post('/tasks/{task}/add-attachment', [TaskController::class, 'addAttachment'])->name('tasks.addAttachment');
        Route::post('/tasks/{task}/attachments', [TaskController::class, 'tasksAttachmentsStore'])->name('tasks.attachments.store');
    });

    Route::middleware('permission:can remove task files')->group(function () {
        Route::delete('/tasks/{task}/attachments/{attachment}', [TaskController::class, 'removeAttachment'])->name('tasks.removeAttachment');
        Route::delete('/attachments/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachments.destroy');
    });

    Route::middleware('permission:can change task status')->group(function () {
        Route::post('/tasks/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    });
    Route::middleware('permission:can edit task')->group(function () {
        Route::post('/tasks/update/{id}', [TaskController::class, 'updateTaskAjax'])->name('tasks.update.json');
    });
    Route::middleware('permission:can remove offsite links')->group(function () {
        Route::delete('/project-keyword/{id}', [ProjectKeywordController::class, 'delete'])->name('project-keyword.delete');
    });

    Route::post('/invoice-payments', [InvoicePaymentController::class, 'store'])->name('invoice-payments.store');
    Route::get('/invoice-payments/{invoiceId}', [InvoicePaymentController::class, 'index']);

    Route::get('/offsite-tasks/{task_id}/starting-kw-urls', [OffsiteTaskController::class, 'getStartingKwUrls'])->name('offsite.tasks.starting_kw_urls');
    Route::get('/offsite-tasks/{task_id}/closing-kw-urls', [OffsiteTaskController::class, 'getClosingKwUrls'])->name('offsite.tasks.closing_kw_urls');
    Route::post('/starting-kw-url/store', [OffsiteTaskController::class, 'store'])->name('starting_kw_url.store');

    Route::get('/task/{task}/comments', [TaskCommentController::class, 'index'])->name('comments.index');
    Route::post('/task/comments', [TaskCommentController::class, 'store'])->name('comments.store');
    Route::put('/task/comments/{id}', [TaskCommentController::class, 'update'])->name('comments.update');
    Route::delete('/task/comments/{id}', [TaskCommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/tasks/{id}/complete', [TaskController::class, 'markAsCompleted'])->name('task.markAsCompleted');
    Route::post('/tasks/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');
    Route::post('/projects/{project}/credentials', [ProjectController::class, 'storeClientCredentials'])->name('projects.storeClientCredentials');
    Route::delete('/projects/{project}/credentials/delete', [ProjectController::class, 'deleteClientCredential'])
        ->name('credentials.delete');
    Route::post('/projects/{project}/keywords', [ProjectKeywordController::class, 'store'])->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('projects.storeKeywords');
    Route::post('/partner-links/multi', [PartnerLinkController::class, 'storeMultiple'])->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('partner-links.store-multiple');
    Route::get('/task/{task}/collaborators', [TaskCollaboratorController::class, 'index'])->name('task.collaborators');
    Route::middleware('permission:can create tasks')->group(function () {
        Route::post('/task/collaborators/store', [TaskCollaboratorController::class, 'store'])->name('task.collaborators.store');
        Route::delete('/task/collaborators/{collaborator}', [TaskCollaboratorController::class, 'destroy'])->name('task.collaborators.destroy');
        Route::post('/projects/{project}/renew', [ProjectController::class, 'renew'])->name('projects.renew');
    });
});
-require __DIR__ . '/auth.php';
