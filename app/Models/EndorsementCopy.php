<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndorsementCopy extends Model
{
    use HasFactory;
    protected $table = 'endorsement_copies';
    protected $fillable = [
        'policy_portfolio_id',
        'document',
        'remark',
    ];
}
