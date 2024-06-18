<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faini extends Model
{
    use HasFactory;

    public function loan():BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'kiasi',
        'user_id',
        'loan_id',
        'customer_id'
    ];
}
