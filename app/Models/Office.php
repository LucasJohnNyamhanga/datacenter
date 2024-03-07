<?php

namespace App\Models;

use App\Models\User;
use App\Models\Mapato;
use App\Models\Customer;
use App\Models\Matumizi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    public function user():HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customer():HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function matumizi():HasMany
    {
        return $this->hasMany(Matumizi::class);
    }

    public function mapato():HasMany
    {
        return $this->hasMany(Mapato::class);
    }

    protected $fillable = [
        'jina',
        'asilimiaMkopo',
        'asilimiaFomu',
    ];

   
}
