<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasUuids;

    protected $fillable = [
        'resource_category_id',
        'name',
        'description',
        'file_path',
        'file_type',
        'is_active',
        'created_by',
    ];


    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function resourceCategory()
    {
        return $this->belongsTo(ResourceCategory::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function customerShares()
    {
        return $this->hasMany(
            CustomerCatalogueShare::class
        );
    }



}
