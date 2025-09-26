<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claims extends Model
{
    use HasFactory;

    protected $table = 'claims';

    protected $fillable = [
        'company_id',
        'policy_portfolio_id',
        'is_marine',

        // Common fields
        'policy_number',
        'policy_period',
        'estimated_loss_amount',
        'date_of_loss',

        // Regular claims fields
        'insured_name',
        'policy_type',
        'brief_description_of_loss',
        'details_of_affected_items',
        'complete_loss_location',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',

        // Marine claims fields
        'name_of_insured',
        'consignor_name_address',
        'consignee_name_address',
        'invoice_no',
        'invoice_date',
        'invoice_value',
        'lr_gr_airway_bl_no',
        'lr_gr_airway_bl_date',
        'transporter_name',
        'driver_name',
        'driver_phone',
        'vehicle_container_no',
        'consignment_received_date',
        'place_of_loss',
        'nature_of_loss',
        'survey_address',
        'spoc_name',
        'spoc_phone',
        'item_commodity_description',
    ];

    protected $casts = [
        'is_marine' => 'boolean',
        'date_of_loss' => 'date',
        'invoice_date' => 'date',
        'lr_gr_airway_bl_date' => 'date',
        'consignment_received_date' => 'date',
        'estimated_loss_amount' => 'decimal:2',
        'invoice_value' => 'decimal:2',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyMaster::class, 'company_id');
    }

    public function policyPortfolio()
    {
        return $this->belongsTo(PolicyPortfolio::class, 'policy_portfolio_id');
    }

    // Scopes
    public function scopeRegular($query)
    {
        return $query->where('is_marine', 0);
    }

    public function scopeMarine($query)
    {
        return $query->where('is_marine', 1);
    }

    // Accessors
    public function getClaimTypeAttribute()
    {
        return $this->is_marine ? 'Marine' : 'Regular';
    }
}
