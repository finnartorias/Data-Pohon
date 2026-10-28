<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Halaman Dashboard Admin dengan ringkasan statistik pohon.
     */
    public function index()
    {
        $totalTrees = Tree::count();
        $totalCarbon = Tree::sum('estimasi_karbon');
        $treesThisMonth = Tree::whereMonth('tanggal_tanam', Carbon::now()->month)
            ->whereYear('tanggal_tanam', Carbon::now()->year)
            ->count();
        $locationsCount = Tree::whereNotNull('lokasi_taman')
            ->distinct('lokasi_taman')
            ->count('lokasi_taman');

        $recentTrees = Tree::latest()->take(5)->get();

        // Data sebaran koordinat untuk mini peta di dashboard
        $treeMarkers = Tree::select('id', 'id_pohon', 'nama_pohon', 'nama_latin', 'kordinat_lat', 'kordinat_lng', 'estimasi_karbon')
            ->whereNotNull('kordinat_lat')
            ->whereNotNull('kordinat_lng')
            ->get();

        return view('dashboard.index', compact(
            'totalTrees',
            'totalCarbon',
            'treesThisMonth',
            'locationsCount',
            'recentTrees',
            'treeMarkers'
        ));
    }
}
