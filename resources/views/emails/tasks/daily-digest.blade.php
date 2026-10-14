@php
    $c = $b['colors'];
    $sections = [
        ['key' => 'overdue',   'title' => 'Overdue',   'count' => $overdue_count,   'color' => $c['danger'],       'empty' => 'Nothing overdue.'],
        ['key' => 'due_today', 'title' => 'Due today', 'count' => $due_today_count, 'color' => $c['warning'],      'empty' => 'Nothing due today.'],
        ['key' => 'upcoming',  'title' => 'Upcoming',  'count' => $upcoming_count,  'color' => $c['primary_dark'], 'empty' => 'Nothing due in the next few days.'],
    ];
@endphp

@include('emails.partials.header', [
    'label'     => 'My Tasks',
    'preheader' => $overdue_count . ' overdue, ' . $due_today_count . ' due today, ' . $upcoming_count . ' upcoming.',
])

@include('emails.partials.hero', [
    'accent'    => $c['primary'],
    'icon'      => '&#9776;',
    'iconColor' => $c['dark'],
    'eyebrow'   => 'My Tasks · Daily digest',
    'meta'      => $digest_date . ' (' . $timezone . ')',
    'title'     => 'Good morning, ' . $recipient_name . '.',
    'intro'     => "Here's what's on your plate today.",
])

{{-- Counters --}}
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $c['bg'] }}; border-radius: 12px;">
            <tr>
                @foreach ($sections as $s)
                    <td class="stack{{ $loop->first ? ' stack-first' : '' }}" width="33%" style="padding: 18px 12px; text-align: center;{{ $loop->first ? '' : ' border-left: 1px solid ' . $c['border'] . ';' }}">
                        <div style="font-size: 26px; font-weight: 800; color: {{ $s['count'] ? $s['color'] : $c['muted'] }};">{{ $s['count'] }}</div>
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }}; margin-top: 4px;">{{ $s['title'] }}</div>
                    </td>
                @endforeach
            </tr>
        </table>
    </td>
</tr>

@foreach ($sections as $s)
    @php $rows = ${$s['key']}; @endphp
    <tr>
        <td class="px" style="padding: 28px 40px 0 40px;">
            <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $s['color'] }}; margin-bottom: 10px;">{{ $s['title'] }} ({{ $s['count'] }})</div>
            @if (count($rows) === 0)
                <div style="font-size: 14px; color: {{ $c['muted'] }}; padding: 6px 0;">{{ $s['empty'] }}</div>
            @else
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
                    @foreach ($rows as $row)
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid {{ $c['border'] }};">
                                <a href="{{ $row['task_link'] }}" style="font-weight: 700; color: {{ $c['text'] }}; text-decoration: none;">{{ $row['task_name'] }}</a>
                                <div style="font-size: 12px; color: {{ $c['muted'] }}; margin-top: 3px;">
                                    {{ $row['task_id'] }} &middot; {{ $row['client'] }} &middot; Due {{ $row['due_date'] }} &middot; {{ $row['status'] }}
                                </div>
                            </td>
                            <td align="right" valign="top" style="padding: 12px 0 12px 10px; border-bottom: 1px solid {{ $c['border'] }}; white-space: nowrap;">
                                <span style="display: inline-block; padding: 4px 10px; font-size: 10px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: {{ $row['priority_color'] }}; border-radius: 50px;">{{ $row['priority'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </table>
                @if ($s['count'] > count($rows))
                    <div style="font-size: 13px; color: {{ $c['muted'] }}; padding-top: 10px;">
                        + {{ $s['count'] - count($rows) }} more — <a href="{{ $board_link }}" style="color: {{ $c['primary_dark'] }};">see them on your board</a>
                    </div>
                @endif
            @endif
        </td>
    </tr>
@endforeach

@include('emails.partials.cta', [
    'url'   => $board_link,
    'label' => 'Open my task board',
    'note'  => 'Sent to ' . e($recipient_email) . ' every working day. <a href="' . e($prefs_link) . '" style="color:' . $c['primary_dark'] . ';">Manage email preferences</a>',
])

@include('emails.partials.footer')
