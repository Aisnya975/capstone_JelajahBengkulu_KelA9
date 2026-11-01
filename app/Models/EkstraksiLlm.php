<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EkstraksiLlm extends Model
{
    protected $fillable = [
        'narasi_id',
        'model_llm',
        'hasil_ekstraksi',
        'status_review',
        'catatan_review',
    ];

    protected $casts = [
        'hasil_ekstraksi' => 'array',
    ];

    public function narasi()
    {
        return $this->belongsTo(Narasi::class);
    }

    public function pengetahuans()
    {
        return $this->hasMany(Pengetahuan::class);
    }
}