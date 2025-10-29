<?php

$daftarProduk = [
    ["kode" => "A001", "nama" => "Indomie Goreng", "harga" => 3500, "stok" => 100],
    ["kode" => "A002", "nama" => "Teh Botol Sosro", "harga" => 4000, "stok" => 50],
    ["kode" => "A003", "nama" => "Susu Ultra Milk", "harga" => 12000, "stok" => 30],
    ["kode" => "A004", "nama" => "Roti Tawar Sari Roti", "harga" => 15000, "stok" => 20],
    ["kode" => "A005", "nama" => "Minyak Goreng Bimoli 1L", "harga" => 18000, "stok" => 15]
];

function cariProduk($arrayProduk, $kodeProduk) {
    foreach ($arrayProduk as $produk) {
        if ($produk["kode"] == strtoupper($kodeProduk)) {
            return $produk;
        }
    }
    return null;
}

function hitungSubtotal($harga, $jumlah) {
    return $harga * $jumlah;
}

function hitungDiskon($total) {
    if ($total >= 100000) {
        return $total * 0.1;
    } else if ($total >= 50000) {
        return $total * 0.05;
    } else {
        return 0;
    }
}

function hitungPPN($jumlah, $persenPPN = 11) {
    return $jumlah * $persenPPN / 100;
}

function kurangiStok(&$produk, $jumlahBeli) {
    $produk["stok"] = $produk["stok"] - $jumlahBeli;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}

function cetakStruk($keranjang, &$daftarProduk) {
    echo "==================================\n";
    echo "     MINIMARKET SEJAHTERA\n";
    echo "==================================\n";
    echo "Tanggal: " . date("d F Y") . "\n\n";
    
    $subtotal = 0;
    
    foreach ($keranjang as $itemKeranjang) {
        $produk = cariProduk($daftarProduk, $itemKeranjang["kode"]);
        if (!$produk) continue;
        
        $subtotalItem = hitungSubtotal($produk["harga"], $itemKeranjang["jumlah"]);
        $subtotal = $subtotal + $subtotalItem;
        
        echo $produk["nama"] . "\n";
        echo formatRupiah($produk["harga"]) . " x " . $itemKeranjang["jumlah"] . " = " . formatRupiah($subtotalItem) . "\n\n";
    }
    
    $nilaiDiskon = hitungDiskon($subtotal);
    if ($subtotal >= 100000) {
        $persenDiskon = 10;
    } else if ($subtotal >= 50000) {
        $persenDiskon = 5;
    } else {
        $persenDiskon = 0;
    }
    
    $setelahDiskon = $subtotal - $nilaiDiskon;
    $nilaiPPN = hitungPPN($setelahDiskon);
    $totalBayar = $setelahDiskon + $nilaiPPN;
    
    echo "----------------------------------\n";
    echo "Subtotal = " . formatRupiah($subtotal) . "\n";
    echo "Diskon ($persenDiskon%) = " . formatRupiah($nilaiDiskon) . "\n";
    echo "Setelah Diskon = " . formatRupiah($setelahDiskon) . "\n";
    echo "PPN 11% = " . formatRupiah($nilaiPPN) . "\n";
    echo "----------------------------------\n";
    echo "TOTAL BAYAR = " . formatRupiah($totalBayar) . "\n";
    echo "==================================\n\n";
    
    foreach ($keranjang as $itemKeranjang) {
        for ($i = 0; $i < count($daftarProduk); $i++) {
            if ($daftarProduk[$i]["kode"] == $itemKeranjang["kode"]) {
                kurangiStok($daftarProduk[$i], $itemKeranjang["jumlah"]);
            }
        }
    }
    
    echo "Stok setelah transaksi:\n";
    foreach ($keranjang as $itemKeranjang) {
        $produk = cariProduk($daftarProduk, $itemKeranjang["kode"]);
        if ($produk) {
            echo "- " . $produk["nama"] . ": " . $produk["stok"] . " pcs\n";
        }
    }
    
    echo "==================================\n";
    echo "Terima kasih sudah belanja!\n";
    echo "==================================\n";
}

$keranjangBelanja = [];

echo "=== DAFTAR PRODUK ===\n";
foreach ($daftarProduk as $produk) {
    echo $produk["kode"] . " - " . $produk["nama"] . " (" . formatRupiah($produk["harga"]) . "), stok: " . $produk["stok"] . "\n";
}
echo "======================\n";

while (true) {
    $inputKode = readline("Masukkan kode produk (atau 'selesai'): ");
    
    if (strtolower($inputKode) == "selesai") {
        break;
    }
    
    $produkDipilih = cariProduk($daftarProduk, $inputKode);
    
    if (!$produkDipilih) {
        echo "Produk tidak ditemukan!\n";
        continue;
    }
    
    $inputJumlah = (int)readline("Masukkan jumlah: ");
    
    if ($inputJumlah > $produkDipilih["stok"]) {
        echo "Stok tidak cukup! Stok tersedia: " . $produkDipilih["stok"] . "\n";
        continue;
    }
    
    $keranjangBelanja[] = ["kode" => strtoupper($inputKode), "jumlah" => $inputJumlah];
    echo "Produk berhasil ditambahkan!\n\n";
}

if (count($keranjangBelanja) > 0) {
    cetakStruk($keranjangBelanja, $daftarProduk);
} else {
    echo "Tidak ada transaksi.\n";
}
?>
