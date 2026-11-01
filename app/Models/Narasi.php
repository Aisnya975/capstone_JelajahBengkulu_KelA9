<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Narasi extends Model
{
    protected $fillable = [
        'judul_narasi',
        'isi_narasi',
        'jenis_sumber',
        'nama_sumber',
        'jenis_informan',
        'lokasi_penelitian',
        'tanggal_pengumpulan',
        'metode_pengumpulan',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    public function ekstraksiLlms()
    {
        return $this->hasMany(EkstraksiLlm::class);
    }

    public function pengetahuans()
    {
        return $this->hasMany(Pengetahuan::class);
    }
}