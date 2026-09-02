<?php

namespace App\Services;

use App\Models\Visit;
use App\Models\CustomerSatisfactionSetting;
use App\Models\CustomerSatisfactionInvitation;
use App\Jobs\SendCustomerSatisfactionEmail;
use Illuminate\Support\Str;

class CustomerSatisfactionService
{
    public function scheduleForVisit(Visit $visit): void
    {

        $settings = CustomerSatisfactionSetting::first();

        if (!$settings || !$settings->is_enabled) {
            return;
        }


        $customer = $visit->customer;


        /*
        * Customer must have an email.
        */
        if (!$customer?->email) {
            return;
        }


        /*
        * Don't create another invitation
        * for the same visit.
        */
        if ($visit->satisfactionInvitation()->exists()) {
            return;
        }


        /*
        * Optional frequency protection.
        */
        $recentInvitation = CustomerSatisfactionInvitation::where('customer_id', $customer->id)->whereNotNull('sent_at')->where('sent_at', '>=', now()->subDays($settings->minimum_days_between_surveys))->exists();


        if ($recentInvitation) {
            return;
        }


        $invitation = CustomerSatisfactionInvitation::create(['visit_id' => $visit->id,

            'customer_id' => $customer->id,

            'sales_rep_id' => $visit->sales_rep_id,

            'email' => $customer->email,

            'token' => Str::random(64),

            'expires_at' => now()->addDays($settings->survey_expiry_days),]);


        SendCustomerSatisfactionEmail::dispatch($invitation->id)->delay(now()->addMinutes($settings->send_after_minutes));
    }
}
