<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdMaster extends Model
{
    use HasFactory;

    protected $table = 'cd_master';

    protected $fillable = [
        'comp_id',
        'insurance_name',
        'cd_ac_name',
        'cd_ac_no',
        'minimum_balance',
        'current_balance',
        'cd_folder',
        'statment_file',
        'status',
    ];

    /**
     * Get the company that owns the CD account.
     */
    public function company()
    {
        return $this->belongsTo(CompanyMaster::class, 'comp_id', 'id');
    }

    /**
     * Get all transactions for this CD account.
     */
    public function transactions()
    {
        return $this->hasMany(CdAccTransaction::class, 'cd_ac_id');
    }

    /**
     * Get policies associated with this CD account.
     */
    public function policies()
    {
        return $this->hasMany(PolicyPortfolio::class, 'cd_ac_id');
    }
}
