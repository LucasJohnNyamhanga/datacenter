<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mdhamini extends Model
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
        'simu',
        'mahusiano',
        'anapoishi',
        'picha',
        'loan_id',
        'customer_id'
    ];

}
