<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    public function loan(){
        return $this->belongsTo(Loan::class);
    }

    protected $fillable = [
        'kiasi',
        'loans_id',
    ];


}
