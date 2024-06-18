<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Faini;
use App\Models\Mapato;
use App\Models\Office;
use App\Models\Rejesho;
use App\Models\Customer;
use App\Models\Matumizi;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public function customer():HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function faini():HasMany
    {
        return $this->hasMany(Faini::class);
    }

    public function rejesho():HasMany
    {
        return $this->hasMany(Rejesho::class);
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

    public function office():BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function balance():HasMany
    {
        return $this->hasMany(Balance::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
        'jinaKamili',
        'username',
        'picha',
        'jinaMdhamini',
        'simuMdhamini',
        'password',
        'isActive',
        'isManager',
        'isAdmin',
        'department_id',
        'office_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        //'password' => 'hashed',
    ];
}
