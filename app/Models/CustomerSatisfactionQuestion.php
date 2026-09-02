<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerSatisfactionQuestion extends Model
{
    use HasUuids;


    protected $fillable = ['question', 'type', 'category', 'is_required', 'is_active', 'sort_order',];

    protected function casts(): array
    {
        return ['is_required' => 'boolean', 'is_active' => 'boolean', 'sort_order' => 'integer',];
    }

}
