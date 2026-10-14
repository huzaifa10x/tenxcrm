{{-- Expects: $c, $url, $label, $note (optional, escaped) --}}
<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: {{ $c['primary'] }}; border-radius: 10px;">
                    <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: {{ $c['dark'] }}; text-decoration: none; letter-spacing: 0.3px;">{{ $label }} &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: {{ $c['muted'] }};">
            Button not working? <a href="{{ $url }}" style="color: {{ $c['primary_dark'] }}; word-break: break-all;">{{ $url }}</a>
        </div>
        @if (!empty($note))
            <div style="margin-top: 16px; font-size: 12px; line-height: 18px; color: {{ $c['muted'] }};">{!! $note !!}</div>
        @endif
    </td>
</tr>
