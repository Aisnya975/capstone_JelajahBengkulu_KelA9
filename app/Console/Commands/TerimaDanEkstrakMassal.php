<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Narasi;
use App\Models\Pengetahuan;
use Carbon\Carbon;

class TerimaDanEkstrakMassal extends Command
{
    protected $signature = 'narasi:terima-semua';
    protected $description = 'Menerima dan mengekstrak seluruh narasi ke tabel pengetahuan secara massal lewat terminal';

    public function handle()
    {
        $narasis = Narasi::all();
        $total = $narasis->count();
        
        $this->info("🚀 Memulai proses verifikasi & ekstraksi untuk {$total} data narasi...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $berhasil = 0;

        foreach ($narasis as $narasi) {
            // 1. Update status verifikasi menjadi terverifikasi
            $narasi->update(['status_verifikasi' => 'terverifikasi']);

            // 2. Masukkan atau perbarui data ke tabel pengetahuans agar tidak [NULL]
            Pengetahuan::updateOrCreate(
                ['narasi_id' => $narasi->id],
                [
                    'name' => $narasi->judul_narasi,
                    'category' => 'Tradisi & Budaya Lokal',
                    'description' => $narasi->isi_narasi,
                    'practice' => 'Praktik atau ritual adat berdasarkan narasi budaya.',
                    'cultural_term' => 'Istilah Budaya Pesisir',
                    'community' => 'Masyarakat Lokal Bengkulu',
                    'people' => $narasi->nama_sumber ?? 'Tokoh Adat / Narasumber',
                    'location' => $narasi->lokasi_penelitian ?? 'Bengkulu',
                    'time' => $narasi->tanggal_pengumpulan ? Carbon::parse($narasi->tanggal_pengumpulan)->format('Y') : 'Turun-temurun',
                    'meaning' => 'Pelestarian nilai luhur budaya lokal.'
                ]
            );

            $berhasil++;
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✨ Selesai! Berhasil menerima dan memproses {$berhasil} data narasi secara massal.");
    }
}