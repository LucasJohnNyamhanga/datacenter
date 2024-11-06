<?php

namespace App\Models;

use App\Models\User;
use App\Models\Mapato;
use App\Models\Customer;
use App\Models\Matumizi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;


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

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user():HasMany
    {
        return $this->hasMany(User::class);
    }

    public function dhamana():HasMany
    {
        return $this->hasMany(Dhamana::class);
    }

    public function balance():HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function aina():HasMany
    {
        return $this->hasMany(Aina::class);
    }

    protected $fillable = [
        'jina',
        'asilimiaMkopo',
        'asilimiaFomu',
        'department_id'
    ];

   
}
