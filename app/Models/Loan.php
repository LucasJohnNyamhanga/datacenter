<?php

namespace App\Models;

use App\Models\Form;
use App\Models\Faini;
use App\Models\Dhamana;
use App\Models\Rejesho;
use App\Models\Customer;
use App\Models\Mdhamini;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dhamana()
    {
        return $this->hasMany(Dhamana::class);
    }

    public function mdhamini()
    {
        return $this->hasMany(Mdhamini::class);
    }

    public function marejesho()
    {
        return $this->hasMany(Rejesho::class);
    }

    public function faini()
    {
        return $this->hasMany(Faini::class);
    }


    public function form()
    {
        return $this->hasOne(Form::class);
    }

    protected $fillable = [
        'siku',
        'kiasi',
        'customer_id',
        'hali',
        'kasoro',
        'maelezo',
        'njeMuda',
        'mpya',
        'riba'
    ];
}
