<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionInvitation extends Model
{
    use HasUuids;

    protected $fillable = ['visit_id', 'customer_id', 'sales_rep_id', 'email', 'token', 'sent_at', 'opened_at', 'completed_at', 'expires_at',];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime', 'opened_at' => 'datetime', 'completed_at' => 'datetime', 'expires_at' => 'datetime',];
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function answers()
    {
        return $this->hasMany(CustomerSatisfactionAnswer::class, 'invitation_id');
    }

}
