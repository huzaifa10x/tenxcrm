@php
    $c = $b['colors'];

    if ($onTime === true) {
        $delivery = ['value' => 'On Time', 'color' => $c['primary_dark']];
    } elseif ($onTime === false) {
        $delivery = ['value' => $lateDays . 'd Late', 'color' => $c['danger']];
    } else {
        $delivery = ['value' => 'No Deadline', 'color' => $c['muted']];
    }
@endphp

@include('emails.partials.header', [
    'label'     => 'Task Completed',
    'preheader' => $completedByName . ' completed "' . $task->title . '" on ' . $completedDate . '.',
])

{{-- Green accent strip --}}
<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: {{ $c['primary'] }};">&nbsp;</td>
</tr>

{{-- Hero --}}
<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: {{ $c['dark'] }};">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="width: 44px; height: 44px; background-color: {{ $c['primary'] }}; border-radius: 50%; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: {{ $c['dark'] }}; font-weight: 800;">&#10003;</td>
                <td style="padding-left: 14px;">
                    <div style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: {{ $c['primary'] }};">Task Completed</div>
                    <div style="font-size: 13px; color: {{ $c['on_dark_muted'] }}; margin-top: 2px;">{{ $completedDate }} at {{ $completedTime }} (GST)</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title" style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">Great news, {{ $recipient }}!</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: {{ $c['on_dark_soft'] }};">
            <strong style="color: #ffffff;">{{ $completedByName }}</strong> has marked a task you created on <strong style="color: #ffffff;">{{ $project }}</strong> as complete. It's ready for your review.
        </p>
    </td>
</tr>

{{-- Task card --}}
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: 1px solid {{ $c['border'] }}; border-left: 5px solid {{ $c['primary'] }}; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div style="font-size: 12px; font-weight: 700; color: {{ $c['muted'] }}; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;">{{ $taskCode }}</div>
                    <div style="font-size: 20px; line-height: 28px; font-weight: 800; color: {{ $c['text'] }}; margin: 6px 0 14px 0;">{{ $task->title }}</div>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['dark'] }}; background-color: {{ $c['primary'] }}; border-radius: 50px;">&#10003; Completed</span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: {{ $priorityColor }}; border-radius: 50px;">{{ $priority }}</span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['primary_dark'] }}; background-color: {{ $c['primary_light'] }}; border-radius: 50px;">{{ $type }}</span>
                </td>
            </tr>
        </table>
    </td>
</tr>

{{-- Performance stats --}}
<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $c['bg'] }}; border-radius: 12px;">
            <tr>
                <td class="stack stack-first" width="33%" style="padding: 18px 12px; text-align: center;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Delivery</div>
                    <div style="font-size: 18px; font-weight: 800; color: {{ $delivery['color'] }}; margin-top: 6px;">{{ $delivery['value'] }}</div>
                </td>
                <td class="stack" width="34%" style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Time Taken</div>
                    <div style="font-size: 16px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 6px;">{{ $timeTaken }}</div>
                </td>
                <td class="stack" width="33%" style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Deliverables</div>
                    <div style="font-size: 16px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 6px;">{{ $attachmentsCount }} file(s)</div>
                </td>
            </tr>
        </table>
    </td>
</tr>

{{-- Details --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['text'] }}; margin-bottom: 10px;">Summary</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="padding: 11px 0; width: 42%; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Project</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $project }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Assigned To</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $assignee }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Completed By</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $completedByName }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Start Date</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $startDate }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Due Date</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $dueDate }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }}; border-bottom: 1px solid {{ $c['border'] }};">Completed On</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['primary_dark'] }}; border-bottom: 1px solid {{ $c['border'] }};">{{ $completedDate }}, {{ $completedTime }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: {{ $c['muted'] }};">Comments</td>
                <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }};">{{ $commentsCount > 0 ? $commentsCount . ' comment(s) on this task' : 'No comments' }}</td>
            </tr>
        </table>
    </td>
</tr>

{{-- Delivery note --}}
@if (!is_null($onTime))
<tr>
    <td class="px" style="padding: 24px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $onTime ? $c['primary_light'] : $c['danger_light'] }}; border-left: 4px solid {{ $onTime ? $c['primary'] : $c['danger'] }}; border-radius: 8px;">
            <tr>
                <td style="padding: 14px 18px; font-size: 14px; line-height: 21px; color: {{ $c['text'] }};">
                    @if ($onTime)
                        <strong style="color: {{ $c['primary_dark'] }};">Delivered on time.</strong> Completed on or before the {{ $dueDate }} deadline.
                    @else
                        <strong style="color: {{ $c['danger'] }};">Delivered late.</strong> Completed <strong>{{ $lateDays }} day(s)</strong> after the {{ $dueDate }} deadline.
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>
@endif

{{-- Next step --}}
<tr>
    <td class="px" style="padding: 24px 40px 0 40px; font-size: 14px; line-height: 22px; color: {{ $c['muted'] }};">
        <strong style="color: {{ $c['text'] }};">Next step:</strong> review the deliverables and leave feedback, or reopen the task if anything needs another pass.
    </td>
</tr>

{{-- CTA --}}
<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: {{ $c['primary'] }}; border-radius: 10px;">
                    <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: {{ $c['dark'] }}; text-decoration: none; letter-spacing: 0.3px;">Review Task &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: {{ $c['muted'] }};">
            Button not working? <a href="{{ $url }}" style="color: {{ $c['primary_dark'] }}; word-break: break-all;">{{ $url }}</a>
        </div>
    </td>
</tr>

@include('emails.partials.footer')