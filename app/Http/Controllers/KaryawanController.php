<?php

namespace App\Http\Controllers;

use App\DTO\KaryawanTetapDTO;
use App\DTO\KaryawanKontrakDTO;
use App\DTO\KaryawanMagangDTO;

// ============================================================
// MATERI 12: CONTROLLER DI LARAVEL
// KaryawanController mewarisi base Controller Laravel
// Ini adalah contoh Inheritance di framework!
// ============================================================
class KaryawanController extends Controller {

    // --------------------------------------------------------
    // METHOD: index()
    // Menampilkan daftar semua karyawan
    // --------------------------------------------------------
    public function index() {

        // MATERI 10: ARRAY OF OBJECTS
        // Membuat array berisi object-object DTO Karyawan
        $karyawan = [
            new KaryawanTetapDTO(
                nama:       'Budi Santoso',
                nip:        'KT-001',
                departemen: 'Teknologi Informasi',
                email:      'budi@perusahaan.id',
                gajiPokok:  5_500_000,
                tunjangan:  1_200_000,
                golongan:   'III-A'
            ),
            new KaryawanTetapDTO(
                nama:       'Sari Dewi Rahayu',
                nip:        'KT-002',
                departemen: 'Keuangan',
                email:      'sari@perusahaan.id',
                gajiPokok:  6_000_000,
                tunjangan:  1_500_000,
                golongan:   'III-B'
            ),
            new KaryawanKontrakDTO(
                nama:         'Ahmad Fauzi',
                nip:          'KK-001',
                departemen:   'Desain Kreatif',
                email:        'ahmad@perusahaan.id',
                gajiPerBulan: 4_500_000,
                masaKontrak:  '12 Bulan'
            ),
            new KaryawanKontrakDTO(
                nama:         'Rina Amalia Putri',
                nip:          'KK-002',
                departemen:   'Marketing',
                email:        'rina@perusahaan.id',
                gajiPerBulan: 4_000_000,
                masaKontrak:  '6 Bulan'
            ),
            new KaryawanMagangDTO(
                nama:         'Deni Pratama',
                nip:          'KM-001',
                departemen:   'Teknologi Informasi',
                email:        'deni@perusahaan.id',
                uangSaku:     1_500_000,
                durasiMinggu: 12
            ),
        ];

        // Hitung total gaji dari semua karyawan
        $totalGaji = array_sum(
            array_map(fn($k) => $k->hitungGaji(), $karyawan)
        );

        $judulHalaman  = 'Portal Karyawan — Daftar Karyawan';
        $jumlahTotal   = count($karyawan);
        $bulanTahun    = now()->translatedFormat('F Y');

        // MATERI 11: compact()
        // Mengemas semua variabel menjadi array asosiatif
        // untuk dikirim ke Blade View
        return view('karyawan.index', compact(
            'karyawan',
            'totalGaji',
            'judulHalaman',
            'jumlahTotal',
            'bulanTahun'
        ));
    }

    // --------------------------------------------------------
    // METHOD: show()
    // Menampilkan detail satu karyawan berdasarkan NIP
    // --------------------------------------------------------
    public function show(string $nip) {
        // Biasanya dari database, tapi kita simulasikan
        $karyawan = $this->cariKaryawanByNip($nip);

        if (!$karyawan) {
            abort(404, 'Karyawan tidak ditemukan!');
        }

        $judulHalaman = "Detail Karyawan: {$karyawan->getNama()}";

        // compact() dengan satu variabel pun tetap valid
        return view('karyawan.show', compact('karyawan', 'judulHalaman'));
    }

    // --------------------------------------------------------
    // METHOD: laporanGaji()
    // Laporan penggajian bulanan
    // --------------------------------------------------------
    public function laporanGaji() {
        $karyawan   = $this->getDaftarKaryawan(); // Ambil semua karyawan
        $totalGaji  = array_sum(array_map(fn($k) => $k->hitungGaji(), $karyawan));
        $periode    = now()->translatedFormat('F Y');
        $judulHalaman = 'Laporan Gaji Bulanan';

        return view('karyawan.laporan', compact(
            'karyawan',
            'totalGaji',
            'periode',
            'judulHalaman'
        ));
    }

    // Helper method private
    private function cariKaryawanByNip(string $nip): ?KaryawanTetapDTO {
        // Implementasi pencarian...
        return null;
    }

    private function getDaftarKaryawan(): array {
        // Return array of objects...
        return [];
    }
}
