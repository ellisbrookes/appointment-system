<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;
  use Billable;

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ownedCompanies()
    {
        return $this->hasMany(Company::class);
    }

    public function companyMemberships()
    {
        return $this->hasMany(CompanyMember::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_members')
                    ->withPivot(['role', 'status', 'joined_at'])
                    ->withTimestamps();
    }

    public function activeCompanies()
    {
        return $this->belongsToMany(Company::class, 'company_members')
                    ->wherePivot('status', 'active')
                    ->withPivot(['role', 'status', 'joined_at'])
                    ->withTimestamps();
    }

    public function isOwnerOf($companyId)
    {
        return $this->companyMemberships()
                    ->where('company_id', $companyId)
                    ->where('role', 'owner')
                    ->exists();
    }

    public function isAdminOf($companyId)
    {
        return $this->companyMemberships()
                    ->where('company_id', $companyId)
                    ->whereIn('role', ['owner', 'admin'])
                    ->exists();
    }

    public function isMemberOf($companyId)
    {
        return $this->companyMemberships()
                    ->where('company_id', $companyId)
                    ->where('status', 'active')
                    ->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone_number',
        'settings'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'settings' => 'array',
        ];
    }
}
