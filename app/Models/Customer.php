<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    public function office(){
        return $this->belongsTo(Office::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function loan(){
        return $this->hasOne(Loan::class);
    }

    protected $fillable = [
        'jina',
        'jinaMaarufu',
        'jinsia',
        'anapoishi',
        'simu',
        'kazi',
        'picha',
        'offices_id',
        'users_id',
    ];

}
