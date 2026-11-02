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
     * Halaman Utama / Portal Eksplorasi Pohon Publik (Tanpa Login)
     */
    public function index()
    {
        return auth()->check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    }

    /**
     * Lembar identitas publik spesimen pohon yang diakses melalui pemindaian QR code fisik.
     * Dapat diakses langsung tanpa autentikasi.
     */
    public function show(string $id_pohon)
    {
        $cleanId = trim($id_pohon);
        $tree = Tree::where('id_pohon', $cleanId)->first();

        // Resolusi kode ID pohon jika terdapat variasi format nomor spesimen
        if (!$tree) {
            if (preg_match('/(\d+)/', $cleanId, $matches)) {
                $numPad = str_pad($matches[1], 3, '0', STR_PAD_LEFT);
                $tree = Tree::where('id_pohon', 'SMKKN-' . $numPad)
                            ->orWhere('id_pohon', 'TREE-' . $numPad)
                            ->orWhere('id_pohon', 'like', "%{$numPad}")
                            ->orWhere('id', (int)$matches[1])
                            ->first();
            }
        }

        if (!$tree) {
            $suggestedTrees = Tree::orderBy('id_pohon', 'asc')->take(6)->get();
            return response()->view('public.not-found', [
                'id_pohon' => $id_pohon,
                'suggestedTrees' => $suggestedTrees,
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
        $cleanId = trim($id_pohon);
        $tree = Tree::where('id_pohon', $cleanId)->first();

        if (!$tree && preg_match('/(\d+)/', $cleanId, $matches)) {
            $numPad = str_pad($matches[1], 3, '0', STR_PAD_LEFT);
            $tree = Tree::where('id_pohon', 'SMKKN-' . $numPad)
                        ->orWhere('id_pohon', 'TREE-' . $numPad)
                        ->orWhere('id_pohon', 'like', "%{$numPad}")
                        ->orWhere('id', (int)$matches[1])
                        ->first();
        }

        if (!$tree) {
            abort(404, 'Tree not found');
        }

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
