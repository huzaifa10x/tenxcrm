{{-- Expects: $c (colors), $b (brand), $label, $preheader --}}
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $label }} | {{ $b['app_name'] }}</title>
    <style>
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px { padding-left: 20px !important; padding-right: 20px !important; }
            .stack { display: block !important; width: 100% !important; border-left: 0 !important; border-top: 1px solid {{ $c['border'] }} !important; }
            .stack-first { border-top: 0 !important; }
            .hero-title { font-size: 22px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: {{ $c['bg'] }}; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color: {{ $c['text'] }}; -webkit-font-smoothing: antialiased;">

    {{-- Inbox preview text (hidden) --}}
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0; mso-hide: all;">{{ $preheader }}&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;</div>

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: {{ $c['bg'] }};">
        <tr>
            <td align="center" style="padding: 32px 12px;">
                <table class="container" width="620" cellpadding="0" cellspacing="0" role="presentation" style="width: 620px; max-width: 620px;">

                    {{-- Top brand bar --}}
                    {{-- <tr>
                        <td style="padding: 0 4px 18px 4px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="left" valign="middle">
                                        <a href="{{ $b['website'] }}" target="_blank" style="text-decoration: none;">
                                            <img src="{{ $b['logo'] }}" alt="{{ $b['company'] }}" height="38" style="display: block; height: 38px; width: auto; border: 0; font-size: 20px; font-weight: 800; color: {{ $c['dark'] }};">
                                        </a>
                                    </td>
                                    <td align="right" valign="middle">
                                        <span style="display: inline-block; padding: 6px 12px; background-color: {{ $c['primary_light'] }}; color: {{ $c['primary_dark'] }}; font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; border-radius: 50px;">{{ $b['app_name'] }} &middot; Tasks</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr> --}}

                    {{-- Card open --}}
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 16px; overflow: hidden;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">