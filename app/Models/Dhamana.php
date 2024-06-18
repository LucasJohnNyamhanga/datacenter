<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dhamana extends Model
{
    use HasFactory;

    public function loan():BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected $fillable = [
        'jina',
        'thamani',
        'maelezo',
        'loan_id',
        'picha',
        'customer_id',
        'office_id'
    ];

}