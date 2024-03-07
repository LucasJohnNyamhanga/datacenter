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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     public function user(){
        return $this->belongsTo(Office::class);
    }

    public function customer(){
        return $this->hasMany(Customer::class);
    }

    public function faini(){
        return $this->hasMany(Faini::class);
    }

    public function rejesho(){
        return $this->hasMany(Rejesho::class);
    }

    public function matumizi(){
        return $this->hasMany(Matumizi::class);
    }

    public function mapato(){
        return $this->hasMany(Mapato::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office',
        'mobile',
        'username',
        'password',
        'active',
        'role',
        'offices_id'
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
