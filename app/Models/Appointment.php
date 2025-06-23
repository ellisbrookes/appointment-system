<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Appointment extends Model
{
    protected $fillable = [
        'service',
        'date',
        'timeslot',
        'user_id',
        'company_id',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the user that owns the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company this appointment belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope appointments to a specific company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope appointments that the user has access to.
     */
    public function scopeAccessibleByUser($query, User $user)
    {
        $companyIds = $user->activeCompanies()->pluck('companies.id');
        
        return $query->whereIn('company_id', $companyIds)
                    ->orWhere(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->whereNull('company_id');
                    });
    }
}
