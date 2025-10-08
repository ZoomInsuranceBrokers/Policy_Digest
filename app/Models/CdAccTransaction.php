<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdAccTransaction extends Model
{
    use HasFactory;

    protected $table = 'cd_acc_transactions';

    protected $fillable = [
        'cd_ac_id',
        'credit_type',
        'transaction_amount',
        'document',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'transaction_amount' => 'decimal:2',
    ];

    /**
     * Get the CD account that owns the transaction.
     */
    public function cdAccount()
    {
        return $this->belongsTo(CdMaster::class, 'cd_ac_id');
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebits($query)
    {
        return $query->where('credit_type', 'DEBIT');
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredits($query)
    {
        return $query->where('credit_type', 'CREDIT');
    }

    /**
     * Get formatted transaction amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->transaction_amount, 2);
    }

    /**
     * Get transaction type badge class
     */
    public function getTypeClassAttribute()
    {
        return $this->credit_type === 'CREDIT' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }
}
