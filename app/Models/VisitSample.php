<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VisitSample extends Model
{
    use HasUuids;

    protected $fillable = [
        'visit_id',
        'sample_id',
        'quantity',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }



    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
