<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionAnswer extends Model
{
    use HasUuids;

    protected $fillable = ['invitation_id', 'question_id', 'rating', 'answer',];

    protected function casts(): array
    {
        return ['rating' => 'integer',];
    }

    /**
     * Satisfaction invitation this answer belongs to.
     */
    public function invitation()
    {
        return $this->belongsTo(CustomerSatisfactionInvitation::class, 'invitation_id');
    }

    /**
     * Question being answered.
     */
    public function question()
    {
        return $this->belongsTo(CustomerSatisfactionQuestion::class, 'question_id');
    }
}
