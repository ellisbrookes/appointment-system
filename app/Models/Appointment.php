<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Appointment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service',
        'date',
        'timeslot',
        'user_id',
        'company_id',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_message',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
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
        // Get companies the user is a member of
        $memberCompanyIds = $user->activeCompanies()->pluck('companies.id');
        
        // Get companies the user owns directly
        $ownedCompanyIds = $user->ownedCompanies()->pluck('id');
        
        // Combine both sets of company IDs
        $allCompanyIds = $memberCompanyIds->merge($ownedCompanyIds)->unique();
        
        // Build the query
        $query->where(function($q) use ($allCompanyIds, $user) {
            // Include appointments from companies the user has access to
            if ($allCompanyIds->isNotEmpty()) {
                $q->whereIn('company_id', $allCompanyIds);
            }
            
            // Include personal appointments (no company_id)
            $q->orWhere(function($subQ) use ($user) {
                $subQ->where('user_id', $user->id)
                     ->whereNull('company_id');
            });
        });
        
        return $query;
    }
}
