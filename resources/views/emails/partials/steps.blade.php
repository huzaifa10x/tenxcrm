{{-- Expects: $c, $heading, $steps = [string, ...] (escaped) --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['text'] }}; margin-bottom: 10px;">{{ $heading }}</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px; line-height: 22px; color: {{ $c['text'] }};">
            @foreach ($steps as $i => $step)
                <tr>
                    <td valign="top" style="width: 34px; padding: 6px 0;">
                        <div style="width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: {{ $c['primary_light'] }}; color: {{ $c['primary_dark'] }}; font-size: 12px; font-weight: 800; text-align: center;">{{ $i + 1 }}</div>
                    </td>
                    <td valign="top" style="padding: 7px 0;">{{ $step }}</td>
                </tr>
            @endforeach
        </table>
    </td>
</tr>
