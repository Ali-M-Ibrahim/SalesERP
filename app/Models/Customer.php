<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasUuids;

    protected $fillable = ['customer_number', 'type', 'name', 'phone', 'email', 'address', 'latitude', 'longitude', 'location_updated_at', 'location_updated_by', 'has_stand', 'stand_last_updated_at', 'created_by', 'converted_at', 'is_active',];

    protected function casts(): array
    {
        return ['latitude' => 'decimal:7', 'longitude' => 'decimal:7',

            'location_updated_at' => 'datetime', 'stand_last_updated_at' => 'datetime', 'converted_at' => 'datetime',

            'has_stand' => 'boolean', 'is_active' => 'boolean',];
    }

    public function assignments()
    {
        return $this->hasMany(CustomerAssignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(CustomerAssignment::class)->where('is_active', true)->latestOfMany('assigned_at');
    }

    public function locations()
    {
        return $this->hasMany(CustomerLocation::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function standHistories()
    {
        return $this->hasMany(CustomerStandHistory::class);
    }

    public function resourceShares()
    {
        return $this->hasMany(CustomerCatalogueShare::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function adminNotes()
    {
        return $this->morphMany(AdminNote::class, 'noteable');
    }


}
