<?php

namespace App\Models;

use App\Models\User;
use App\Models\Mapato;
use App\Models\Customer;
use App\Models\Matumizi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;

    public function user(){
        return $this->hasMany(User::class);
    }

    public function customer(){
        return $this->hasMany(Customer::class);
    }

    public function matumizi(){
        return $this->hasMany(Matumizi::class);
    }

    public function mapato(){
        return $this->hasMany(Mapato::class);
    }

    protected $fillable = [
        'jina',
        'asilimiaMkopo',
        'asilimiaFomu',
    ];

   
}
