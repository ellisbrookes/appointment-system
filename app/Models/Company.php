<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'postcode',
        'description',
        'url',
        'user_id',
    ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

  public function members()
  {
      return $this->hasMany(CompanyMember::class);
  }

  public function activeMembers()
  {
      return $this->hasMany(CompanyMember::class)->where('status', 'active');
  }

  public function pendingInvites()
  {
      return $this->hasMany(CompanyMember::class)->where('status', 'invited');
  }

  public function users()
  {
      return $this->belongsToMany(User::class, 'company_members')
                  ->withPivot(['role', 'status', 'joined_at'])
                  ->withTimestamps();
  }

  public function owner()
  {
      return $this->hasOneThrough(
          User::class,
          CompanyMember::class,
          'company_id',
          'id',
          'id',
          'user_id'
      )->where('company_members.role', 'owner');
  }

  public function isMember($userId)
  {
      return $this->members()->where('user_id', $userId)->where('status', 'active')->exists();
  }

  public function hasPendingInvite($userId)
  {
      return $this->members()->where('user_id', $userId)->where('status', 'invited')->exists();
  }

  public function getMemberRole($userId)
  {
      $member = $this->members()->where('user_id', $userId)->first();
      return $member ? $member->role : null;
  }

  /**
   * Get all appointments for this company.
   */
  public function appointments()
  {
      return $this->hasMany(Appointment::class);
  }
}
