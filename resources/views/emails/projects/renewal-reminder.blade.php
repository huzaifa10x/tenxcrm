@php
    $c = $b['colors'];
    $urgent = $stage <= 30;
    $accent = $urgent ? $c['warning'] : $c['primary'];
@endphp

@include('emails.partials.header', [
    'label'     => 'Renewal in ' . $stage . ' days',
    'preheader' => $account_name . ' renews on ' . $renewal_date . ' (' . $account_mrr . '/month).',
])

@include('emails.partials.hero', [
    'accent'    => $accent,
    'icon'      => '&#8635;',
    'iconColor' => $c['dark'],
    'eyebrow'   => $urgent ? 'Action needed · Renewal in 30 days' : 'Renewal in 60 days',
    'meta'      => 'Renewal date: ' . $renewal_date,
    'title'     => 'Hi ' . $account_manager . ',',
    'intro'     => $urgent
        ? '<strong style="color:#ffffff;">' . e($account_name) . '</strong> renews in 30 days. The renewal conversation should be happening now.'
        : 'A retainer renewal is 60 days out. Early enough to plan the play, not so early it\'s ignored.',
])

{{-- Account card --}}
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: 1px solid {{ $c['border'] }}; border-left: 5px solid {{ $accent }}; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div style="font-size: 12px; font-weight: 700; color: {{ $c['muted'] }}; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;">{{ $account_id }}</div>
                    <div style="font-size: 20px; line-height: 28px; font-weight: 800; color: {{ $c['text'] }}; margin: 6px 0 4px 0;">{{ $account_name }}</div>
                    @if ($client_name)
                        <div style="font-size: 14px; color: {{ $c['muted'] }}; margin-bottom: 14px;">{{ $client_name }}</div>
                    @else
                        <div style="height: 10px; line-height: 10px; font-size: 0;">&nbsp;</div>
                    @endif
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['primary_dark'] }}; background-color: {{ $c['primary_light'] }}; border-radius: 50px;">{{ $plan_name }}</span>
                    @if ($is_high_value)
                        <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: {{ $c['dark'] }}; background-color: {{ $c['warning'] }}; border-radius: 50px;">&#9733; High Value</span>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>

{{-- Stats --}}
<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $c['bg'] }}; border-radius: 12px;">
            <tr>
                <td class="stack stack-first" width="33%" style="padding: 18px 12px; text-align: center;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Monthly Value</div>
                    <div style="font-size: 17px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 6px;">{{ $account_mrr }}</div>
                </td>
                <td class="stack" width="34%" style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Renewal Date</div>
                    <div style="font-size: 16px; font-weight: 800; color: {{ $c['text'] }}; margin-top: 6px;">{{ $renewal_date }}</div>
                </td>
                <td class="stack" width="33%" style="padding: 18px 12px; text-align: center; border-left: 1px solid {{ $c['border'] }};">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: {{ $c['muted'] }};">Days Away</div>
                    <div style="font-size: 22px; font-weight: 800; color: {{ $accent }}; margin-top: 4px;">{{ $days_until_renewal ?? '—' }}</div>
                </td>
            </tr>
        </table>
    </td>
</tr>

@php
    $rows = [
        'Account'        => $account_name,
        'Plan / service' => $plan_name,
        'Monthly value'  => $account_mrr,
    ];
    if ($urgent) {
        $rows['Renewal date'] = $renewal_date . ' (' . $days_until_renewal . ' days away)';
        $rows['Renewal status'] = $renewal_status;
    } else {
        $rows['Client since'] = $start_date;
        $rows['Renewal date'] = $renewal_date;
    }
    $rows['Contract value'] = $contract_value;
@endphp

@include('emails.partials.details', ['heading' => 'Account', 'rows' => $rows])

@include('emails.partials.steps', $urgent ? [
    'heading' => "If you haven't yet",
    'steps' => [
        'Book or confirm the renewal conversation this week.',
        'Send the renewal terms / new agreement for sign-off.',
        'Update the renewal status in the CRM so we can track what\'s at risk.',
    ],
] : [
    'heading' => 'This week',
    'steps' => [
        'Review account health — recent performance, deliverables, any red flags.',
        'Decide the direction: straight renewal, upsell, or renegotiation.',
        'Note any risks now so there\'s time to address them before the conversation.',
    ],
])

@if ($urgent)
    @include('emails.partials.callout', [
        'bg'     => $c['warning_light'],
        'border' => $c['warning'],
        'html'   => '<strong style="color:#B7791F;">Not renewing?</strong> Flag it now so Finance and delivery can plan the wind-down.',
    ])
@endif

@include('emails.partials.cta', ['url' => $account_link, 'label' => 'Open the account'])

@include('emails.partials.footer')
