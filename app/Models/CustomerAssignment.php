<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerAssignment extends Model
{
    use HasUuids;


    protected $fillable = ['customer_id', 'sales_rep_id', 'assigned_by', 'assigned_at', 'ended_at', 'is_active',];


    protected function casts(): array
    {
        return ['assigned_at' => 'datetime', 'ended_at' => 'datetime', 'is_active' => 'boolean',];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }


}
