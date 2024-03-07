<?php

namespace App\Models;

use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matumizi extends Model
{
    use HasFactory;

    public function office(){
        return $this->belongsTo(Office::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'kiasi',
        'njia',
        'aina',
        'maelezo',
        'offices_id',
        'users_id',
    ];

}
