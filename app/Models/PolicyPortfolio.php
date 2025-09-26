<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyPortfolio extends Model
{
    use HasFactory;
    protected $table = 'policy_portfolio';
    protected $fillable = [
        'company_id',
        'insured_name',
        'product_name',
        'policy_number',
        'start_date',
        'expiry_date',
        'sum_insured',
        'pbst',
        'gross_premium',
        'insurance_company_name',
        'cash_deposit',
        'policy_copy',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyMaster::class, 'company_id');
    }
}
