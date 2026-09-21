<?php

// ============================================================
// MATERI 9: INHERITANCE — CLASS PRODUK
// ============================================================

// CLASS INDUK
abstract class Produk {
    protected string $nama;
    protected float  $harga;
    protected int    $stok;

    public function __construct(string $nama, float $harga, int $stok) {
        $this->nama  = $nama;
        $this->harga = $harga;
        $this->stok  = $stok;
    }

    // Method getter untuk properti protected
    public function getNama(): string  { return $this->nama; }
    public function getHarga(): float  { return $this->harga; }
    public function getStok(): int     { return $this->stok; }

    // Method abstract — HARUS diimplementasikan oleh child class
    abstract public function getTipe(): string;
    abstract public function getKeterangan(): string;

    // Method umum yang diwarisi
    public function hitungSubtotal(int $qty): float {
        return $this->harga * $qty;
    }
}

// ============================================================
// CLASS ANAK 1: ProdukFisik
// Produk yang memiliki berat dan biaya kirim
// ============================================================
class ProdukFisik extends Produk {
    private float $beratKg;
    private float $biayaKirim;

    public function __construct(
        string $nama, float $harga, int $stok,
        float $beratKg, float $biayaKirim
    ) {
        parent::__construct($nama, $harga, $stok);
        $this->beratKg    = $beratKg;
        $this->biayaKirim = $biayaKirim;
    }

    public function getTipe(): string {
        return "Produk Fisik 📦";
    }

    public function getKeterangan(): string {
        return "Berat: {$this->beratKg} kg | Ongkir: Rp " . number_format($this->biayaKirim, 0, ',', '.');
    }

    // Override hitungSubtotal: tambah biaya kirim
    public function hitungSubtotal(int $qty): float {
        return ($this->harga * $qty) + $this->biayaKirim;
    }
}

// ============================================================
// CLASS ANAK 2: ProdukDigital
// Produk yang berupa file/lisensi, tidak ada ongkir
// ============================================================
class ProdukDigital extends Produk {
    private string $formatFile;
    private string $lisensi;

    public function __construct(
        string $nama, float $harga, int $stok,
        string $formatFile, string $lisensi
    ) {
        parent::__construct($nama, $harga, $stok);
        $this->formatFile = $formatFile;
        $this->lisensi    = $lisensi;
    }

    public function getTipe(): string {
        return "Produk Digital 💻";
    }

    public function getKeterangan(): string {
        return "Format: {$this->formatFile} | Lisensi: {$this->lisensi}";
    }

    // Produk digital: tidak ada ongkos kirim tambahan
    public function hitungSubtotal(int $qty): float {
        return $this->harga * $qty; // Tidak ada biaya kirim
    }
}

// ============================================================
// MATERI 10: ARRAY OF OBJECTS — Keranjang Belanja
// ============================================================
$keranjang = [
    // [0] => object ProdukFisik dengan qty
    ['produk' => new ProdukFisik("Laptop Gaming ASUS ROG", 15_000_000, 10, 2.5, 50_000), 'qty' => 1],
    ['produk' => new ProdukFisik("Mechanical Keyboard", 850_000, 25, 0.8, 15_000),        'qty' => 2],
    ['produk' => new ProdukDigital("Adobe Photoshop 2024", 1_200_000, 999, "EXE", "1 Tahun"), 'qty' => 1],
    ['produk' => new ProdukDigital("Template Website Premium", 350_000, 999, "ZIP", "Seumur Hidup"), 'qty' => 3],
];

// ============================================================
// MATERI 11: FUNGSI compact()
// Mengemas variabel menjadi array asosiatif
// ============================================================

// Hitung total belanja
$totalBelanja = 0;
foreach ($keranjang as $item) {
    $totalBelanja += $item['produk']->hitungSubtotal($item['qty']);
}

$judul         = "Invoice Belanja - TokoDigital.id";
$tanggal       = date("d F Y, H:i");
$nomorInvoice  = "INV-" . strtoupper(uniqid());
$diskon        = $totalBelanja > 10_000_000 ? $totalBelanja * 0.05 : 0;
$grandTotal    = $totalBelanja - $diskon;

// compact() mengemas SEMUA variabel di atas menjadi satu array
$dataInvoice = compact(
    'judul',
    'tanggal',
    'nomorInvoice',
    'keranjang',
    'totalBelanja',
    'diskon',
    'grandTotal'
);

// ============================================================
// FUNGSI RENDER INVOICE — Menerima data dari compact()
// ============================================================
function renderInvoice(array $data): void {
    // Menggunakan extract() untuk "membuka" array compact
    extract($data);

    echo "\n╔══════════════════════════════════════════════════╗\n";
    echo "║          {$judul}\n";
    echo "╠══════════════════════════════════════════════════╣\n";
    echo "║  No. Invoice : {$nomorInvoice}\n";
    echo "║  Tanggal     : {$tanggal}\n";
    echo "╠══════════════════════════════════════════════════╣\n";

    foreach ($keranjang as $i => $item) {
        $p       = $item['produk'];
        $q       = $item['qty'];
        $sub     = $p->hitungSubtotal($q);
        $no      = $i + 1;
        echo "║  [{$no}] {$p->getTipe()}\n";
        echo "║      {$p->getNama()}\n";
        echo "║      {$p->getKeterangan()}\n";
        echo "║      Qty: {$q} × Rp " . number_format($p->getHarga(), 0, ',', '.') . "\n";
        echo "║      Subtotal: Rp " . number_format($sub, 0, ',', '.') . "\n";
        echo "║  " . str_repeat("·", 48) . "\n";
    }

    echo "║  Total Belanja : Rp " . number_format($totalBelanja, 0, ',', '.') . "\n";
    if ($diskon > 0) {
        echo "║  Diskon 5%     : -Rp " . number_format($diskon, 0, ',', '.') . "\n";
    }
    echo "║  GRAND TOTAL   : Rp " . number_format($grandTotal, 0, ',', '.') . "\n";
    echo "╚══════════════════════════════════════════════════╝\n";
}

// Memanggil fungsi dengan data dari compact()
renderInvoice($dataInvoice);