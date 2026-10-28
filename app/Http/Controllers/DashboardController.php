<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Halaman Dashboard Admin dengan analitik mendalam dan ringkasan statistik pohon.
     */
    public function index()
    {
        $totalTrees = Tree::count();
        $totalCarbon = (float) Tree::sum('estimasi_karbon');
        $totalBiomass = (float) Tree::sum('biomassa_agb');
        $totalCarbonStock = (float) Tree::sum('cadangan_karbon');

        $avgDiameter = round((float) Tree::whereNotNull('diameter')->avg('diameter'), 1);
        $avgTinggi = round((float) Tree::whereNotNull('tinggi')->avg('tinggi'), 1);

        $treesThisMonth = Tree::whereMonth('tanggal_tanam', Carbon::now()->month)
            ->whereYear('tanggal_tanam', Carbon::now()->year)
            ->count();
            
        $locationsCount = Tree::whereNotNull('lokasi_taman')
            ->distinct('lokasi_taman')
            ->count('lokasi_taman');

        // Distribusi Tingkat Pertumbuhan (Pohon, Tiang, Pancang)
        $tingkatCounts = Tree::selectRaw('COALESCE(tingkat, "Lainnya") as tingkat, count(*) as count')
            ->groupBy('tingkat')
            ->pluck('count', 'tingkat')
            ->toArray();

        // 5 Spesies Pohon Terbanyak beserta serapan karbonnya
        $topSpecies = Tree::selectRaw('nama_pohon, count(*) as total, sum(estimasi_karbon) as total_karbon')
            ->groupBy('nama_pohon')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Pohon Terbaru
        $recentTrees = Tree::latest()->take(6)->get();

        // Ekuivalen Emisi Total
        $totalCarKm = round($totalCarbon * 4.1);
        $totalPhoneCharges = round($totalCarbon * 122);

        // Data sebaran koordinat untuk mini peta di dashboard
        $treeMarkers = Tree::select('id', 'id_pohon', 'nama_pohon', 'nama_latin', 'kordinat_lat', 'kordinat_lng', 'estimasi_karbon')
            ->whereNotNull('kordinat_lat')
            ->whereNotNull('kordinat_lng')
            ->get();

        return view('dashboard.index', compact(
            'totalTrees',
            'totalCarbon',
            'totalBiomass',
            'totalCarbonStock',
            'avgDiameter',
            'avgTinggi',
            'treesThisMonth',
            'locationsCount',
            'tingkatCounts',
            'topSpecies',
            'recentTrees',
            'totalCarKm',
            'totalPhoneCharges',
            'treeMarkers'
        ));
    }
}
