<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AdminNote extends Model
{
    use HasUuids;

    protected $fillable = ['noteable_type', 'noteable_id', 'created_by', 'sales_rep_id', 'note', 'is_important', 'read_at',];

    protected function casts(): array
    {
        return ['is_important' => 'boolean', 'read_at' => 'datetime',];
    }

    public function noteable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }
}
