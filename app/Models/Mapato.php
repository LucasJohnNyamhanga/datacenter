<?php

namespace App\Models;

use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mapato extends Model
{
    use HasFactory;

    public function office():BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'kiasi',
        'njia',
        'aina',
        'maelezo',
        'office_id',
        'user_id',
        'kiasiCopy',
        'njiaCopy',
        'ainaCopy',
        'maelezoCopy',
        'futa',
        'badili',
        'rejeshoId',
        'rejesho'
    ];
}
