<?php
$produk = [
    ["kode" => "A001", "nama" => "Indomie Goreng", "harga" => 3500, "stok" => 100],
    ["kode" => "A002", "nama" => "Teh Botol Sosro", "harga" => 4000, "stok" => 50],
    ["kode" => "A003", "nama" => "Susu Ultra Milk", "harga" => 12000, "stok" => 30],
    ["kode" => "A004", "nama" => "Roti Tawar Sari Roti", "harga" => 15000, "stok" => 20],
    ["kode" => "A005", "nama" => "Minyak Goreng Bimoli 1L", "harga" => 18000, "stok" => 15]
];

function cariProduk($array_produk, $kode) {
    foreach ($array_produk as $p) {
        if ($p["kode"] == $kode) {
            return $p;
        }
    }
    return null;
}

function hitungSubtotal($harga, $jumlah) {
    return $harga * $jumlah;
}

function hitungDiskon($total) {
    if ($total >= 100000) {
        return $total * 0.10;
    } elseif ($total >= 50000) {
        return $total * 0.05;
    } else {
        return 0;
    }
}

function hitungPajak($total, $persen = 11) {
    return $total * ($persen / 100);
}

function kurangiStok(&$produk, $jumlah) {
    $produk["stok"] -= $jumlah;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function buatStrukBelanja($transaksi, &$array_produk) {
    echo "========================================\n";
    echo "         MINIMARKET SEJAHTERA\n";
    echo "========================================\n";
    echo "Tanggal: " . date("d F Y") . "\n\n";

    $subtotal = 0;

    foreach ($transaksi as $t) {
        $produk = cariProduk($array_produk, $t["kode"]);

        if ($produk != null) {
            $harga = $produk["harga"];
            $jumlah = $t["jumlah"];
            $total = hitungSubtotal($harga, $jumlah);

            echo $produk["nama"] . "\n";
            echo formatRupiah($harga) . " x " . $jumlah . " = " . formatRupiah($total) . "\n\n";

            $subtotal += $total;
            kurangiStok($array_produk[array_search($produk, $array_produk)], $jumlah);
        }
    }

    $diskon = hitungDiskon($subtotal);
    $setelah_diskon = $subtotal - $diskon;
    $pajak = hitungPajak($setelah_diskon);
    $total_bayar = $setelah_diskon + $pajak;
