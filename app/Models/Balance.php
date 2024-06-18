<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends Model
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
        'kiasiSystem',
        'kiasiUser',
        'aina',
        'maelezo',
        'office_id',
        'user_id',
        'tareheHesabu',
        'tareheHesabuIliyopita',
        'hesabuIliyopita',
        'imeharibika'
    ];
}
