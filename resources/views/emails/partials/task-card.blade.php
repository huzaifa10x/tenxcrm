{{-- Expects: $c, $task_id, $task_name, $client, $priority, $priority_color, $status, $accent --}}
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: 1px solid {{ $c['border'] }}; border-left: 5px solid {{ $accent }}; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div style="font-size: 12px; font-weight: 700; color: {{ $c['muted'] }}; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;">{{ $task_id }}</div>
                    <div style="font-size: 20px; line-height: 28px; font-weight: 800; color: {{ $c['text'] }}; margin: 6px 0 4px 0;">{{ $task_name }}</div>
                    <div style="font-size: 14px; color: {{ $c['muted'] }}; margin-bottom: 14px;">{{ $client }}</div>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: {{ $priority_color }}; border-radius: 50px;">{{ $priority }}</span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['text'] }}; background-color: {{ $c['bg'] }}; border-radius: 50px;">{{ $status }}</span>
                </td>
            </tr>
        </table>
    </td>
</tr>
