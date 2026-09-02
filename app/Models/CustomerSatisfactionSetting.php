<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionSetting extends Model
{
    use HasUuids;

    protected $fillable = ['is_enabled', 'send_after_minutes', 'minimum_days_between_surveys', 'survey_expiry_days', 'email_subject', 'email_intro', 'include_visit_summary', 'include_samples', 'low_rating_threshold', 'notify_on_low_rating',];

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean', 'include_visit_summary' => 'boolean', 'include_samples' => 'boolean', 'notify_on_low_rating' => 'boolean',

            'send_after_minutes' => 'integer', 'minimum_days_between_surveys' => 'integer', 'survey_expiry_days' => 'integer', 'low_rating_threshold' => 'integer',];
    }

}
