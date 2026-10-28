<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublicTreeController extends Controller
{
    /**
     * Halaman Publik Detail Pohon saat QR Code di-scan oleh masyarakat/pengunjung.
     * Tidak memerlukan login.
     */
    public function show(string $id_pohon)
    {
        $tree = Tree::where('id_pohon', $id_pohon)->first();

        if (!$tree) {
            return response()->view('public.not-found', [
                'id_pohon' => $id_pohon
            ], 404);
        }

        // Kalkulasi ekuivalen jejak karbon untuk visual edukatif pengunjung
        // 1 kg CO2 terserap ≈ ~4 km perjalanan mobil bensin rata-rata
        $carKmEquivalent = round($tree->estimasi_karbon * 4.1, 1);
        // 1 kg CO2 ≈ ~120 kali pengisian daya baterai smartphone
        $phoneChargesEquivalent = round($tree->estimasi_karbon * 122);

        return view('public.tree-detail', compact(
            'tree',
            'carKmEquivalent',
            'phoneChargesEquivalent'
        ));
    }

    /**
     * Sajikan SVG QR Code secara langsung jika file storage belum ter-generate.
     */
    public function rawQr(string $id_pohon)
    {
        $tree = Tree::where('id_pohon', $id_pohon)->firstOrFail();

        $publicUrl = route('pohon.public', $tree->id_pohon);

        $svg = QrCode::format('svg')
            ->size(350)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($publicUrl);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
