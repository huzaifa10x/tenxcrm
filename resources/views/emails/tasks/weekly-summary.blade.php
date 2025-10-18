@component('mail::message')
# Hi {{ $user->name }},

Here's your weekly task summary for **{{ now()->format('l, F d, Y') }}**.

@component('mail::panel')
<div style="text-align: center; padding: 20px 0;">
    <div style="font-size: 14px; color: #6c757d; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">
        <strong>⚠️ PRIORITY</strong>
    </div>
    <div style="font-size: 48px; font-weight: bold; color: #dc3545; line-height: 1.2; margin-bottom: 8px;">
        {{ $todoCount ?? 0 }}
    </div>
    <div style="font-size: 18px; color: #495057; font-weight: 600;">
        Pending Tasks
    </div>
</div>
@endcomponent

<div style="display: flex; justify-content: space-around; text-align: center; margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
    <div style="flex: 1; padding: 10px;">
        <div style="font-size: 28px; font-weight: bold; color: #ffc107;">
            {{ $inProgressCount ?? 0 }}
        </div>
        <div style="font-size: 14px; color: #6c757d; margin-top: 5px;">
            🚧 In Progress
        </div>
    </div>
    <div style="flex: 1; padding: 10px; border-left: 2px solid #dee2e6;">
        <div style="font-size: 28px; font-weight: bold; color: #17a2b8;">
            {{ $reviewCount ?? 0 }}
        </div>
        <div style="font-size: 14px; color: #6c757d; margin-top: 5px;">
            🔍 Review
        </div>
    </div>
</div>

@if(($summary['Todo'] ?? 0) > 0)
<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <strong style="color: #856404;">💡 Reminder:</strong>
    <span style="color: #856404;">You have <strong>{{ $summary['Todo'] ?? 0 }} pending tasks</strong> that require immediate attention.</span>
</div>
@endif

<div style="margin: 30px 0; text-align: center;">
@component('mail::button', ['url' => 'https://10xksa.com/tasks', 'color' => 'error'])
View My Tasks
@endcomponent
</div>

<div style="text-align: center; color: #6c757d; font-size: 14px; margin-top: 30px; margin-bottom: 40px; padding-top: 20px; border-top: 1px solid #dee2e6;">
    Keep pushing forward and close those pending tasks this week! 🚀
</div>

Thanks,<br>
**10X ONE CRM**
@endcomponent
