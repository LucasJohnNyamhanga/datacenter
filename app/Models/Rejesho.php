<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rejesho extends Model
{
    use HasFactory;

    public function loan(){
        return $this->belongsTo(Loan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'kiasi',
        'users_id',
        'loans_id',
    ];

}
