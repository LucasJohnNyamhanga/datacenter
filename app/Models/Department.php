<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Department extends Model
{
    use HasFactory;

    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }

    public function office():HasMany
    {
        return $this->hasMany(Office::class);
    }


    public function customer():HasManyThrough
    {
        return $this->hasManyThrough(Customer::class, Office::class);
    }

    protected $fillable = [
        'name',
        'manager_id',
    ];

}
