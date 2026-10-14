{{-- Expects: $c, $heading, $rows = [label => value] (values are escaped) --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['text'] }}; margin-bottom: 10px;">{{ $heading }}</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            @foreach ($rows as $label => $value)
                <tr>
                    <td style="padding: 11px 0; width: 42%; color: {{ $c['muted'] }};{{ $loop->last ? '' : ' border-bottom: 1px solid ' . $c['border'] . ';' }}">{{ $label }}</td>
                    <td style="padding: 11px 0; font-weight: 600; color: {{ $c['text'] }};{{ $loop->last ? '' : ' border-bottom: 1px solid ' . $c['border'] . ';' }}">{{ $value }}</td>
                </tr>
            @endforeach
        </table>
    </td>
</tr>
