<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengetahuan extends Model
{
    use HasFactory;

    protected $table = 'pengetahuans';

    // Kolom-kolom yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
    'narasi_id',
    'ekstraksi_llm_id',
    'name',
    'category',
    'description',
    'practice',
    'cultural_term',
    'community',
    'people',
    'location',
    'time',
    'meaning',
    'image', // <-- Pastikan ini ada!
];

    // Mengubah format kolom JSON di database menjadi array otomatis di PHP
    protected $casts = [
        'praktik' => 'array',
        'istilah_budaya' => 'array',
        'komunitas' => 'array',
        'tokoh' => 'array',
    ];

    public function narasi()
    {
        return $this->belongsTo(Narasi::class);
    }
}