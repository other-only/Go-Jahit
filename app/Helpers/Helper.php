<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}
