@php
    $c = $b['colors'];
    $row = 'padding: 11px 0; border-bottom: 1px solid ' . $c['border'] . ';';
    $label = 'width: 42%; color: ' . $c['muted'] . ';';
    $val = 'font-weight: 600; color: ' . $c['text'] . ';';
    $section =
        'font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: ' .
        $c['text'] .
        '; margin-bottom: 10px;';
@endphp

@include('emails.partials.header', [
    'label' => 'Cancellation Request',
    'preheader' =>
        $requesterName . ' requested to cancel ' . $accountName . ' (' . $accountMrr . '/mo). Review needed.',
])

{{-- Accent strip --}}
<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: {{ $c['danger'] }};">&nbsp;</td>
</tr>

{{-- Hero --}}
<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: {{ $c['dark'] }};">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td
                    style="width: 44px; height: 44px; background-color: {{ $c['danger'] }}; border-radius: 12px; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: #ffffff; font-weight: 800;">
                    !</td>
                <td style="padding-left: 14px;">
                    <div
                        style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: {{ $c['danger'] }};">
                        Action Required</div>
                    <div style="font-size: 13px; color: {{ $c['on_dark_muted'] }}; margin-top: 2px;">{{ $requestDate }}
                        at {{ $requestTime }} ({{ $timezone }})</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title"
            style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">Hi
            {{ $adminName }}, a cancellation needs your review.</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: {{ $c['on_dark_soft'] }};">
            <strong style="color: #ffffff;">{{ $requesterName }}</strong> submitted a request to cancel <strong
                style="color: #ffffff;">{{ $accountName }}</strong>. Nothing changes until you approve it.
        </p>
    </td>
</tr>

{{-- Account card --}}
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="border: 1px solid {{ $c['border'] }}; border-left: 5px solid {{ $c['danger'] }}; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: {{ $c['muted'] }}; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;">
                        {{ $accountId }}</div>
                    <div
                        style="font-size: 20px; line-height: 28px; font-weight: 800; color: {{ $c['text'] }}; margin: 6px 0 4px 0;">
                        {{ $accountName }}</div>
                    @if ($clientName)
                        <div style="font-size: 14px; color: {{ $c['muted'] }}; margin-bottom: 14px;">
                            {{ $clientName }}</div>
                    @else
                        <div style="height: 10px; line-height: 10px; font-size: 0;">&nbsp;</div>
                    @endif
                    <span
                        style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: {{ $c['danger'] }}; border-radius: 50px;">Cancellation
                        Pending</span>
                    <span
                        style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['primary_dark'] }}; background-color: {{ $c['primary_light'] }}; border-radius: 50px;">{{ $planName }}</span>
                    @if ($isHighValue)
                        <span
                            style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['dark'] }}; background-color: {{ $c['warning'] }}; border-radius: 50px;">&#9733;
                            High Value</span>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>

{{-- Impact stats --}}
<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="background-color: {{ $c['bg'] }}; border-radius: 12px;">
            <tr>
                <td class="stack stack-first" width="33%" style="padding: 18px 12px; text-align: center;">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">
                        Monthly Value</div>
                    <div style="font-size: 17px; font-weight: 800; color: {{ $c['danger'] }}; margin-top: 6px;">
                        {{ $accountMrr }}</div>
                </td>
                <td class="stack" width="34%"
                    style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">
                        Client For</div>
                    <div style="font-size: 16px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 6px;">
                        {{ $tenure ?? 'N/A' }}</div>
                </td>
                <td class="stack" width="33%"
                    style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">
                        Open Tasks</div>
                    <div style="font-size: 20px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 4px;">
                        {{ $openTasks }}</div>
                </td>
            </tr>
        </table>
    </td>
</tr>

{{-- Request --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="{{ $section }}">Request</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="{{ $row }} {{ $label }}">Requested By</td>
                <td style="{{ $row }} {{ $val }}">{{ $requesterName }}<br><a
                        href="mailto:{{ $requesterEmail }}"
                        style="font-size: 12px; font-weight: 400; color: {{ $c['primary_dark'] }}; text-decoration: none;">{{ $requesterEmail }}</a>
                </td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Role</td>
                <td style="{{ $row }} {{ $val }}">{{ $requesterRole }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; {{ $label }}">Submitted</td>
                <td style="padding: 11px 0; {{ $val }}">{{ $requestDate }} at {{ $requestTime }}
                    {{ $timezone }}</td>
            </tr>
        </table>
    </td>
</tr>

{{-- Account --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="{{ $section }}">Account</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="{{ $row }} {{ $label }}">Plan / Service</td>
                <td style="{{ $row }} {{ $val }}">{{ $planName }}</td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Contract Value</td>
                <td style="{{ $row }} {{ $val }}">{{ $contractValue }}</td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Monthly Value</td>
                <td style="{{ $row }} {{ $val }}">{{ $accountMrr }}</td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Client Since</td>
                <td style="{{ $row }} {{ $val }}">{{ $startDate }}</td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Current Contract Ends</td>
                <td style="{{ $row }} {{ $val }}">{{ $contractEnd }}</td>
            </tr>
            <tr>
                <td style="{{ $row }} {{ $label }}">Project Status</td>
                <td style="{{ $row }} {{ $val }}">{{ $projectStatus }}</td>
            </tr>
            <tr>
                <td style="padding: 11px 0; {{ $label }}">Account Owner (AM)</td>
                <td style="padding: 11px 0; {{ $val }}">{{ $accountManager }}</td>
            </tr>
        </table>
    </td>
</tr>

{{-- Cancellation details --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="{{ $section }}">Cancellation Details</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="padding: 11px 0; {{ $label }}">Requested Effective Date</td>
                <td style="padding: 11px 0; {{ $val }}">{{ $effectiveDate }}</td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="background-color: {{ $c['danger_light'] }}; border-radius: 12px; margin-top: 8px;">
            <tr>
                <td style="padding: 18px 20px;">
                    <div
                        style="font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['danger'] }}; margin-bottom: 8px;">
                        Reason Given</div>
                    <div style="font-size: 14px; line-height: 22px; color: {{ $c['text'] }};">{{ $reason }}
                    </div>
                </td>
            </tr>
        </table>
        @if ($additionalNotes)
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                style="background-color: {{ $c['bg'] }}; border-radius: 12px; margin-top: 12px;">
                <tr>
                    <td style="padding: 18px 20px;">
                        <div
                            style="font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }}; margin-bottom: 8px;">
                            Notes</div>
                        <div style="font-size: 14px; line-height: 22px; color: {{ $c['text'] }};">
                            {{ $additionalNotes }}</div>
                    </td>
                </tr>
            </table>
        @endif
    </td>
</tr>

{{-- Retention alert --}}
@if ($isHighValue)
    <tr>
        <td class="px" style="padding: 20px 40px 0 40px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                style="background-color: {{ $c['warning_light'] }}; border-left: 4px solid {{ $c['warning'] }}; border-radius: 8px;">
                <tr>
                    <td style="padding: 14px 18px; font-size: 14px; line-height: 21px; color: {{ $c['text'] }};">
                        <strong style="color: #B7791F;">High-value account.</strong> At {{ $accountMrr }}/month,
                        loop in <strong>{{ $accountManager }}</strong> for a retention attempt before approving.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endif

{{-- Next steps --}}
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="{{ $section }}">Next Steps</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="font-size: 14px; line-height: 22px; color: {{ $c['text'] }};">
            @foreach (['Review the request and confirm the account details above.', 'If this is a high-value or at-risk account, loop in ' . $accountManager . ' for a retention attempt before processing.', 'Approve or decline the cancellation in 10X One.' . ($openTasks > 0 ? ' Approving will block ' . $openTasks . ' open task(s).' : '')] as $i => $step)
                <tr>
                    <td valign="top" style="width: 34px; padding: 6px 0;">
                        <div
                            style="width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: {{ $c['primary_light'] }}; color: {{ $c['primary_dark'] }}; font-size: 12px; font-weight: 800; text-align: center;">
                            {{ $i + 1 }}</div>
                    </td>
                    <td valign="top" style="padding: 7px 0;">{{ $step }}</td>
                </tr>
            @endforeach
        </table>
    </td>
</tr>

{{-- CTA --}}
<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: {{ $c['primary'] }}; border-radius: 10px;">
                    <a href="{{ $url }}" target="_blank"
                        style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: {{ $c['dark'] }}; text-decoration: none; letter-spacing: 0.3px;">Review
                        Request &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: {{ $c['muted'] }};">
            Button not working? <a href="{{ $url }}"
                style="color: {{ $c['primary_dark'] }}; word-break: break-all;">{{ $url }}</a>
        </div>
    </td>
</tr>

@include('emails.partials.footer')
