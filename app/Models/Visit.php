<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasUuids;

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';


    protected $fillable = ['customer_id', 'sales_rep_id', 'visit_purpose_id', 'purpose_other', 'scheduled_at',

        'check_in_at', 'check_in_latitude', 'check_in_longitude', 'check_in_accuracy',

        'check_out_at', 'check_out_latitude', 'check_out_longitude', 'check_out_accuracy',

        'status', 'visit_notes', 'client_requests', 'created_by', 'contact_point', 'contact_point_position'];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime',

            'check_in_at' => 'datetime', 'check_out_at' => 'datetime',

            'check_in_latitude' => 'decimal:7', 'check_in_longitude' => 'decimal:7', 'check_in_accuracy' => 'decimal:2',

            'check_out_latitude' => 'decimal:7', 'check_out_longitude' => 'decimal:7', 'check_out_accuracy' => 'decimal:2',];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function visitPurpose()
    {
        return $this->belongsTo(VisitPurpose::class);
    }

    public function visitSamples()
    {
        return $this->hasMany(VisitSample::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checkInCheckOutDistance(): ?float
    {
        if (!$this->check_in_latitude || !$this->check_in_longitude || !$this->check_out_latitude || !$this->check_out_longitude) {
            return null;
        }

        return $this->distanceBetweenCoordinates((float)$this->check_in_latitude, (float)$this->check_in_longitude, (float)$this->check_out_latitude, (float)$this->check_out_longitude);
    }


    public function checkInCustomerDistance(): ?float
    {
        if (!$this->customer || !$this->customer->latitude || !$this->customer->longitude || !$this->check_in_latitude || !$this->check_in_longitude) {
            return null;
        }

        return $this->distanceBetweenCoordinates((float)$this->customer->latitude, (float)$this->customer->longitude, (float)$this->check_in_latitude, (float)$this->check_in_longitude);
    }


    public function checkOutCustomerDistance(): ?float
    {
        if (!$this->customer || !$this->customer->latitude || !$this->customer->longitude || !$this->check_out_latitude || !$this->check_out_longitude) {
            return null;
        }

        return $this->distanceBetweenCoordinates((float)$this->customer->latitude, (float)$this->customer->longitude, (float)$this->check_out_latitude, (float)$this->check_out_longitude);
    }


    private function distanceBetweenCoordinates(float $lat1, float $lon1, float $lat2, float $lon2): float
    {

        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $latTo = deg2rad($lat2);

        $latDelta = deg2rad($lat2 - $lat1);

        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }


    public function adminNotes()
    {
        return $this->morphMany(AdminNote::class, 'noteable');
    }

    public function satisfactionInvitation()
    {
        return $this->hasOne(CustomerSatisfactionInvitation::class);
    }

}
