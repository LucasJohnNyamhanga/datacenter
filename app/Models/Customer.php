<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loan():HasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected $fillable = [
        'jina',
        'jinaMaarufu',
        'jinsia',
        'anapoishi',
        'simu',
        'kazi',
        'picha',
        'office_id',
        'user_id',
    ];

}
