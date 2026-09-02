<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'phone', 'password', 'is_active', 'last_login_at',];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean', 'last_login_at' => 'datetime',];
    }

    public function customerAssignments()
    {
        return $this->hasMany(CustomerAssignment::class, 'sales_rep_id');
    }

    public function assignedCustomers()
    {
        return $this->belongsToMany(Customer::class, 'customer_assignments', 'sales_rep_id', 'customer_id')->wherePivot('is_active', true)->withPivot(['assigned_at', 'ended_at', 'is_active']);
    }

    public function salesVisits()
    {
        return $this->hasMany(
            Visit::class,
            'sales_rep_id'
        );
    }

}
