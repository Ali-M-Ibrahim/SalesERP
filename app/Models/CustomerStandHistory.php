<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerStandHistory extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'has_stand',
        'notes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'has_stand' => 'boolean',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
