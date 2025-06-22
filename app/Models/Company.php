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
      'user_id',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
