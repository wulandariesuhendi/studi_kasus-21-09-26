<?php

// ============================================================
// MATERI 9: INHERITANCE & PROTECTED
// ============================================================

// CLASS INDUK (PARENT CLASS)
class Karyawan {
    // Properti 'protected' hanya bisa diakses oleh class ini
    // dan class turunannya (child class)
    protected string $nama;
    protected string $nip;
    protected string $departemen;

    // Constructor: dipanggil saat object dibuat
    public function __construct(string $nama, string $nip, string $departemen) {
        $this->nama        = $nama;
        $this->nip         = $nip;
        $this->departemen  = $departemen;
    }

    // Method yang bisa digunakan oleh child class
    public function getInfo(): string {
        return "Nama: {$this->nama} | NIP: {$this->nip} | Dept: {$this->departemen}";
    }

    // Method hitung gaji — akan di-override oleh child class
    public function hitungGaji(): float {
        return 0;
    }
}

// ============================================================
// CLASS ANAK 1: KaryawanTetap
// Menggunakan keyword 'extends' untuk mewarisi class Karyawan
// ============================================================
class KaryawanTetap extends Karyawan {
    private float $gajiPokok;
    private float $tunjangan;

    public function __construct(
        string $nama,
        string $nip,
        string $departemen,
        float  $gajiPokok,
        float  $tunjangan
    ) {
        // Memanggil constructor dari class induk (Karyawan)
        parent::__construct($nama, $nip, $departemen);
        $this->gajiPokok = $gajiPokok;
        $this->tunjangan = $tunjangan;
    }

    // Override method hitungGaji dari parent
    public function hitungGaji(): float {
        return $this->gajiPokok + $this->tunjangan;
    }

    public function getStatus(): string {
        return "Karyawan Tetap";
    }
}

// ============================================================
// CLASS ANAK 2: KaryawanParuhWaktu
// Juga mewarisi class Karyawan
// ============================================================
class KaryawanParuhWaktu extends Karyawan {
    private int   $jumlahJam;
    private float $tarifPerJam;

    public function __construct(
        string $nama,
        string $nip,
        string $departemen,
        int    $jumlahJam,
        float  $tarifPerJam
    ) {
        parent::__construct($nama, $nip, $departemen);
        $this->jumlahJam   = $jumlahJam;
        $this->tarifPerJam = $tarifPerJam;
    }

    // Override: gaji dihitung dari jam kerja × tarif
    public function hitungGaji(): float {
        return $this->jumlahJam * $this->tarifPerJam;
    }

    public function getStatus(): string {
        return "Karyawan Paruh Waktu";
    }
}

// ============================================================
// MATERI 10: ARRAY OF OBJECTS
// Menyimpan banyak object dalam satu array
// ============================================================
$daftarKaryawan = [
    new KaryawanTetap("Budi Santoso",   "KT-001", "IT",       5_500_000, 1_200_000),
    new KaryawanTetap("Sari Dewi",      "KT-002", "Finance",  6_000_000, 1_500_000),
    new KaryawanParuhWaktu("Andi Pratama",  "KP-001", "Design",   120, 35_000),
    new KaryawanParuhWaktu("Rina Amalia",   "KP-002", "Marketing", 80, 40_000),
    new KaryawanTetap("Deni Kurniawan", "KT-003", "HRD",      5_000_000, 1_000_000),
];

// ============================================================
// MENAMPILKAN DATA — Loop menggunakan foreach
// ============================================================
echo "=== LAPORAN PENGGAJIAN BULANAN ===\n";
echo str_repeat("-", 50) . "\n";

$totalGaji = 0;

foreach ($daftarKaryawan as $index => $karyawan) {
    $no    = $index + 1;
    $gaji  = $karyawan->hitungGaji();
    $totalGaji += $gaji;

    echo "\n[{$no}] " . $karyawan->getStatus() . "\n";
    echo "    " . $karyawan->getInfo() . "\n";
    echo "    Gaji Bulan Ini : Rp " . number_format($gaji, 0, ',', '.') . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "TOTAL PENGELUARAN GAJI: Rp " . number_format($totalGaji, 0, ',', '.') . "\n";
echo str_repeat("=", 50) . "\n";