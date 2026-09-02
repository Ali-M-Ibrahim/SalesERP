<?php

namespace Database\Seeders;

use App\Models\CustomerSatisfactionSetting;
use Illuminate\Database\Seeder;

class CustomerSatisfactionSettingSeeder extends Seeder
{
    public function run(): void
    {
        CustomerSatisfactionSetting::updateOrCreate([/*
                 * Singleton settings record.
                 * We use the email subject as the lookup
                 * only to keep the seeder repeatable.
                 */ 'email_subject' => 'Thank you for your time',], ['is_enabled' => true,

                /*
                 * Send feedback email 60 minutes
                 * after the visit is completed.
                 */ 'send_after_minutes' => 60,

                /*
                 * Don't send another survey to the
                 * same customer within 7 days.
                 */ 'minimum_days_between_surveys' => 7,

                /*
                 * Feedback link expires after 14 days.
                 */ 'survey_expiry_days' => 14,

                'email_intro' => 'Thank you for taking the time to meet with our team. We appreciate the opportunity to discuss your needs and how we can support you.',

                'include_visit_summary' => true,

                'include_samples' => true,

                /*
                 * Ratings of 1 or 2 are considered
                 * low ratings.
                 */ 'low_rating_threshold' => 2,

                'notify_on_low_rating' => true,]);
    }
}
