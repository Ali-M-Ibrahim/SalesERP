<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerCatalogueShare extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'resource_id',
        'shared_by',
        'method',
        'shared_at',
    ];

    protected function casts(): array
    {
        return [
            'shared_at' => 'datetime',
        ];
    }




    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

}
