<?php

namespace App\Helpers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\PengajuanPembelian;
use App\Models\Sparepart;
use App\Models\StockOpname;

class CodeGenerator
{
    /**
     * Generate sparepart code: SP-001, SP-002, dst
     */
    public static function sparepartCode(): string
    {
        $last = Sparepart::orderByRaw("CAST(SUBSTRING(code, 4) AS UNSIGNED) DESC")->first();
        $next = $last ? (int) substr($last->code, 3) + 1 : 1;
        return 'SP-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate invoice no barang masuk: INV-YYYYMMDD-001
     */
    public static function invoiceNo(): string
    {
        $prefix = 'INV-' . now()->format('Ymd');
        $last = BarangMasuk::where('invoice_no', 'LIKE', $prefix . '-%')
            ->orderByRaw("CAST(SUBSTRING(invoice_no, -3) AS UNSIGNED) DESC")
            ->first();
        $next = $last ? (int) substr($last->invoice_no, -3) + 1 : 1;
        return $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate reference no barang keluar: BK-YYYYMMDD-001
     */
    public static function referenceNo(): string
    {
        $prefix = 'BK-' . now()->format('Ymd');
        $last = BarangKeluar::where('reference_no', 'LIKE', $prefix . '-%')
            ->orderByRaw("CAST(SUBSTRING(reference_no, -3) AS UNSIGNED) DESC")
            ->first();
        $next = $last ? (int) substr($last->reference_no, -3) + 1 : 1;
        return $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate permintaan no karyawan: RQS-YYYYMMDD-001
     */
    public static function permintaanNo(): string
    {
        $prefix = 'RQS-' . now()->format('Ymd');
        $last = BarangKeluar::where('reference_no', 'LIKE', $prefix . '-%')
            ->orderByRaw("CAST(SUBSTRING(reference_no, -3) AS UNSIGNED) DESC")
            ->first();
        $next = $last ? (int) substr($last->reference_no, -3) + 1 : 1;
        return $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate ajuan no pengajuan: PGJ-YYYYMM-001
     */
    public static function ajuanNo(): string
    {
        $prefix = 'PGJ-' . now()->format('Ym');
        $last = PengajuanPembelian::where('ajuan_no', 'LIKE', $prefix . '-%')
            ->orderByRaw("CAST(SUBSTRING(ajuan_no, -3) AS UNSIGNED) DESC")
            ->first();
        $next = $last ? (int) substr($last->ajuan_no, -3) + 1 : 1;
        return $prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate opname no: SO-YYYY-MM-A
     */
    public static function opnameNo(): string
    {
        $prefix = 'SO-' . now()->format('Y-m');
        $last = StockOpname::where('opname_no', 'LIKE', $prefix . '-%')
            ->orderByDesc('opname_no')
            ->first();

        if ($last) {
            $suffix = substr($last->opname_no, -1);
            $next = chr(ord($suffix) + 1);
        } else {
            $next = 'A';
        }

        return $prefix . '-' . $next;
    }
}
