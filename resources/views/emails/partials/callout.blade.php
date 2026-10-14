{{-- Expects: $c, $bg, $border, $html (already escaped) --}}
<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $bg }}; border-left: 4px solid {{ $border }}; border-radius: 8px;">
            <tr>
                <td style="padding: 14px 18px; font-size: 14px; line-height: 22px; color: {{ $c['text'] }};">{!! $html !!}</td>
            </tr>
        </table>
    </td>
</tr>
