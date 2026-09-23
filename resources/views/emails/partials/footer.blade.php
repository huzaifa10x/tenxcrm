{{-- Expects: $c (colors), $b (brand), $dashboardUrl --}}
                            </table>
                        </td>
                    </tr>
                    {{-- Card close --}}

                    {{-- Footer --}}
                    <tr>
                        <td style="padding-top: 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $c['dark'] }}; border-radius: 16px;">
                                <tr>
                                    <td class="px" style="padding: 28px 36px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding-bottom: 16px; border-bottom: 1px solid {{ $c['on_dark_line'] }};">
                                                    <div style="font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px;">{{ $b['company'] }}</div>
                                                    <div style="font-size: 13px; color: {{ $c['primary'] }}; margin-top: 4px; font-weight: 600;">{{ $b['tagline'] }}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px; font-size: 13px; line-height: 22px; color: {{ $c['on_dark_soft'] }};">
                                                    {{ $b['address'] }}<br>
                                                    <a href="tel:{{ preg_replace('/\s+/', '', $b['phone']) }}" style="color: {{ $c['on_dark_soft'] }}; text-decoration: none;">{{ $b['phone'] }}</a>
                                                    &nbsp;&middot;&nbsp;
                                                    <a href="mailto:{{ $b['email'] }}" style="color: {{ $c['on_dark_soft'] }}; text-decoration: none;">{{ $b['email'] }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 14px; font-size: 13px;">
                                                    @foreach ($b['socials'] as $name => $link)
                                                        <a href="{{ $link }}" target="_blank" style="color: {{ $c['primary'] }}; text-decoration: none; font-weight: 600;">{{ $name }}</a>@if (!$loop->last)<span style="color: {{ $c['on_dark_sep'] }};">&nbsp;&nbsp;|&nbsp;&nbsp;</span>@endif
                                                    @endforeach
                                                    <span style="color: {{ $c['on_dark_sep'] }};">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                                    <a href="{{ $b['website'] }}" target="_blank" style="color: {{ $c['primary'] }}; text-decoration: none; font-weight: 600;">Website</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 18px 20px 0 20px; font-size: 12px; line-height: 18px; color: {{ $c['muted'] }};">
                            This is an automated notification from <strong>{{ $b['app_name'] }}</strong>, {{ $b['company'] }}'s internal task manager.<br>
                            <a href="{{ $dashboardUrl }}" style="color: {{ $c['primary_dark'] }}; text-decoration: underline;">Open your task board</a>
                            &nbsp;&middot;&nbsp; &copy; {{ date('Y') }} {{ $b['company'] }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>