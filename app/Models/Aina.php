<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aina extends Model
{
    use HasFactory;

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    protected $fillable = [
        'jina',
        'riba',
        'siku',
        'office_id'
    ];
}
