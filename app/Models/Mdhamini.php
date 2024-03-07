<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mdhamini extends Model
{
    use HasFactory;

    public function loan(){
        return $this->belongsTo(Loan::class);
    }

    protected $fillable = [
        'jina',
        'simu',
        'mahusiano',
        'anapoishi',
        'picha',
        'loans_id',
    ];

}
