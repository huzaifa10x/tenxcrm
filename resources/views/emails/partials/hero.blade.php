{{-- Expects: $c, $accent, $icon, $iconColor (optional), $eyebrow, $meta, $title, $intro (HTML-safe, already escaped) --}}
<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: {{ $accent }};">&nbsp;</td>
</tr>
<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: {{ $c['dark'] }};">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="width: 44px; height: 44px; background-color: {{ $accent }}; border-radius: 12px; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: {{ $iconColor ?? '#ffffff' }}; font-weight: 800;">{!! $icon !!}</td>
                <td style="padding-left: 14px;">
                    <div style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: {{ $accent }};">{{ $eyebrow }}</div>
                    <div style="font-size: 13px; color: {{ $c['on_dark_muted'] }}; margin-top: 2px;">{{ $meta }}</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title" style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">{{ $title }}</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: {{ $c['on_dark_soft'] }};">{!! $intro !!}</p>
    </td>
</tr>
