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
    public function index(Request $request)
    {
        $query = Tree::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama_pohon', 'like', "%{$search}%")
                  ->orWhere('id_pohon', 'like', "%{$search}%")
                  ->orWhere('nama_latin', 'like', "%{$search}%")
                  ->orWhere('lokasi_taman', 'like', "%{$search}%");
            });
        }

        $totalTrees = Tree::count();
        $totalCarbon = Tree::sum('estimasi_karbon');
        $locationsCount = Tree::whereNotNull('lokasi_taman')->distinct('lokasi_taman')->count('lokasi_taman');

        $trees = $query->orderBy('nama_pohon', 'asc')->paginate(12)->withQueryString();

        return view('welcome', compact('trees', 'totalTrees', 'totalCarbon', 'locationsCount'));
    }

    /**
     * Halaman Publik Detail Pohon saat QR Code di-scan oleh masyarakat/pengunjung.
     * Tidak memerlukan login. Mendukung Smart Fallback ID (misal TREE-001 -> SMKKN-001).
     */
    public function show(string $id_pohon)
    {
        $cleanId = trim($id_pohon);
        $tree = Tree::where('id_pohon', $cleanId)->first();

        // Smart Fallback jika kode ID menggunakan alias atau variasi prefix (misal TREE-001 vs SMKKN-001)
        if (!$tree) {
            // 1. Ekstrak angka dari ID (misal '001' dari 'TREE-001' atau '1')
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
