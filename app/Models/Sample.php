<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasUuids;

    protected $fillable = [
        'sample_category_id',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /*
     * Sample category
     */
    public function sampleCategory()
    {
        return $this->belongsTo(SampleCategory::class);
    }

    /*
     * Visits where this sample was given.
     */
    public function visitSamples()
    {
        return $this->hasMany(VisitSample::class);
    }

    /*
     * Direct access to visits.
     */
    public function visits()
    {
        return $this->belongsToMany(
            Visit::class,
            'visit_samples'
        )->withPivot('quantity')
            ->withTimestamps();
    }
}
