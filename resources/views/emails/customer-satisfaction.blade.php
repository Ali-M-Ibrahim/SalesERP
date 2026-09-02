<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>
        {{ $settings?->email_subject ?? 'Thank you for your time' }}
    </title>

</head>

<body style="
    margin:0;
    padding:0;
    background:#f5f4ef;
    font-family:Arial, Helvetica, sans-serif;
    color:#222;
">

<table width="100%"
       cellpadding="0"
       cellspacing="0"
       border="0"
       style="background:#f5f4ef;
              padding:30px 15px;">

    <tr>

        <td align="center">

            <table width="100%"
                   cellpadding="0"
                   cellspacing="0"
                   border="0"
                   style="
                       max-width:650px;
                       background:#ffffff;
                       border-radius:12px;
                       overflow:hidden;
                   ">


                {{-- Header --}}
                <tr>
                    <td style="
        background:black;
        padding:25px 30px;
        text-align:center;
    ">

                        {{-- Logo --}}
                        <img
                            src="https://hijaziwood.com/website/images/logo/logo_white.png"
                            alt="{{ config('app.name') }}"
                            style="
                display:block;
                max-width:180px;
                max-height:70px;
                width:auto;
                height:auto;
                margin:0 auto 16px;
                border:0;
                outline:none;
                text-decoration:none;
            "
                        >


                        <h2 style="
            margin:0;
            color:#ffffff;
            font-size:22px;
            font-weight:bold;
            line-height:1.4;
        ">
                            Thank you for your time
                        </h2>

                    </td>
                </tr>


                {{-- Main Content --}}
                <tr>

                    <td style="
                        padding:30px;
                    ">

                        <p style="
                            margin-top:0;
                            font-size:15px;
                        ">

                            Dear
                            <strong>
                                {{ $invitation->customer?->name ?? 'Customer' }}
                            </strong>,

                        </p>


                        @if($settings?->email_intro)

                            <p style="
                                font-size:14px;
                                line-height:1.6;
                            ">

                                {{ $settings->email_intro }}

                            </p>

                        @else

                            <p style="
                                font-size:14px;
                                line-height:1.6;
                            ">

                                Thank you for taking the time to meet with our team.
                                We appreciate the opportunity to discuss your needs
                                and how we can support you.

                            </p>

                        @endif



                        {{-- Visit Information --}}
                        <table width="100%"
                               cellpadding="0"
                               cellspacing="0"
                               style="
                                   margin-top:25px;
                                   border:1px solid #DAD4C3;
                                   border-radius:8px;
                               ">

                            <tr>

                                <td style="
                                    padding:15px;
                                    border-bottom:1px solid #DAD4C3;
                                    font-size:13px;
                                ">

                                    <strong>
                                        Sales Representative
                                    </strong>

                                </td>

                                <td style="
                                    padding:15px;
                                    border-bottom:1px solid #DAD4C3;
                                    font-size:13px;
                                    text-align:right;
                                ">

                                    {{ $invitation
                                        ->visit
                                        ?->salesRep
                                        ?->name
                                        ?? '—' }}

                                </td>

                            </tr>


                            <tr>

                                <td style="
                                    padding:15px;
                                    border-bottom:1px solid #DAD4C3;
                                    font-size:13px;
                                ">

                                    <strong>
                                        Visit Date
                                    </strong>

                                </td>

                                <td style="
                                    padding:15px;
                                    border-bottom:1px solid #DAD4C3;
                                    font-size:13px;
                                    text-align:right;
                                ">

                                    {{ $invitation
                                        ->visit
                                        ?->scheduled_at
                                        ?->format('d M Y H:i')
                                        ?? '—' }}

                                </td>

                            </tr>


                            <tr>

                                <td style="
                                    padding:15px;
                                    font-size:13px;
                                ">

                                    <strong>
                                        Purpose
                                    </strong>

                                </td>

                                <td style="
                                    padding:15px;
                                    font-size:13px;
                                    text-align:right;
                                ">

                                    {{ $invitation
                                        ->visit
                                        ?->visitPurpose
                                        ?->name
                                        ?? $invitation
                                            ->visit
                                            ?->purpose_other
                                        ?? '—' }}

                                </td>

                            </tr>

                        </table>



                        {{-- Customer Summary --}}
                        @if(
                            $settings?->include_visit_summary
                            &&
                            $invitation->visit?->customer_summary
                        )

                            <div style="
                                margin-top:25px;
                                padding:18px;
                                background:#F1EFE7;
                                border-radius:8px;
                            ">

                                <p style="
                                    margin:0 0 8px 0;
                                    font-weight:bold;
                                    font-size:14px;
                                ">

                                    Meeting Summary

                                </p>


                                <p style="
                                    margin:0;
                                    font-size:14px;
                                    line-height:1.6;
                                ">

                                    {{ $invitation
                                        ->visit
                                        ->customer_summary }}

                                </p>

                            </div>

                        @endif



                        {{-- Samples --}}
                        @if(
                            $settings?->include_samples
                            &&
                            $invitation
                                ->visit
                                ?->visitSamples
                                ?->isNotEmpty()
                        )

                            <div style="margin-top:25px;">

                                <p style="
                                    font-weight:bold;
                                    margin-bottom:10px;
                                ">

                                    Samples Provided

                                </p>


                                <ul style="
                                    margin:0;
                                    padding-left:20px;
                                    font-size:14px;
                                    line-height:1.8;
                                ">

                                    @foreach(
                                        $invitation
                                            ->visit
                                            ->visitSamples
                                        as $visitSample
                                    )

                                        <li>

                                            {{ $visitSample
                                                ->sample
                                                ?->name
                                                ?? 'Sample' }}

                                            × {{ $visitSample->quantity }}

                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif



                        {{-- Feedback --}}
                        <div style="
                            margin-top:30px;
                            text-align:center;
                        ">

                            <p style="
                                font-size:14px;
                                line-height:1.6;
                            ">

                                Your feedback helps us improve our service,
                                products and customer experience.

                            </p>


                            <a href="{{ route(
                                'satisfaction.show',
                                $invitation->token
                            ) }}"
                               style="
                                   display:inline-block;
                                   margin-top:12px;
                                   padding:13px 24px;
                                   background:#D6772F;
                                   color:#ffffff;
                                   text-decoration:none;
                                   border-radius:7px;
                                   font-size:14px;
                                   font-weight:bold;
                               ">

                                Leave Feedback

                            </a>

                        </div>



                        {{-- Expiry --}}
                        @if($invitation->expires_at)

                            <p style="
                                margin-top:25px;
                                color:#777;
                                font-size:11px;
                                text-align:center;
                            ">

                                This feedback link will remain available until

                                {{ $invitation
                                    ->expires_at
                                    ->format('d M Y') }}.

                            </p>

                        @endif

                    </td>

                </tr>


                {{-- Footer --}}
                <tr>

                    <td style="
                        background:#F1EFE7;
                        padding:20px 30px;
                        text-align:center;
                        font-size:11px;
                        color:#62685F;
                    ">

                        Thank you for choosing us.

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>
</html>
