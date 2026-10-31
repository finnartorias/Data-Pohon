<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TreeController extends Controller
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Tampilkan daftar pohon dengan filter & pencarian.
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

        $trees = $query->latest()->paginate(10)->withQueryString();

        return view('trees.index', compact('trees'));
    }

    /**
     * Tampilkan form penambahan pohon baru.
     */
    public function create()
    {
        // Berikan saran ID Pohon berikutnya (SMKKN-039 dsb)
        $latestTree = Tree::latest('id')->first();
        $prefix = 'SMKKN-';
        if ($latestTree && preg_match('/^([A-Za-z]+-)/', $latestTree->id_pohon, $m)) {
            $prefix = $m[1];
        }
        $latestId = Tree::max('id') ?? 0;
        $suggestedCode = $prefix . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);

        return view('trees.create', compact('suggestedCode'));
    }

    /**
     * Simpan data pohon baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pohon' => ['required', 'string', 'max:50', 'unique:trees,id_pohon'],
            'nama_pohon' => ['required', 'string', 'max:150'],
            'nama_latin' => ['nullable', 'string', 'max:150'],
            'diameter' => ['nullable', 'numeric', 'min:0'],
            'tinggi' => ['nullable', 'numeric', 'min:0'],
            'berat_jenis' => ['nullable', 'numeric', 'min:0'],
            'tanggal_tanam' => ['nullable', 'date'],
            'kordinat_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'kordinat_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'estimasi_karbon' => ['required', 'numeric', 'min:0'],
            'lokasi_taman' => ['nullable', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'], // Max 3MB
        ], [
            'id_pohon.required' => 'ID / Kode pohon wajib diisi.',
            'id_pohon.unique' => 'Kode pohon ini sudah digunakan.',
            'nama_pohon.required' => 'Nama pohon wajib diisi.',
            'estimasi_karbon.required' => 'Estimasi serapan karbon wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal 3MB.',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('trees', 'public');
            $validated['foto'] = $fotoPath;
        }

        // Tree::created event di model Tree akan otomatis men-generate file QR code & menyimpan path-nya
        $tree = Tree::create($validated);

        return redirect()->route('trees.show', $tree)
            ->with('success', "Pohon '{$tree->nama_pohon}' ({$tree->id_pohon}) berhasil ditambahkan dan QR Code siap digunakan!");
    }

    /**
     * Tampilkan detail pohon di dashboard admin.
     */
    public function show(Tree $tree)
    {
        return view('trees.show', compact('tree'));
    }

    /**
     * Tampilkan form edit pohon.
     */
    public function edit(Tree $tree)
    {
        return view('trees.edit', compact('tree'));
    }

    /**
     * Perbarui data pohon.
     */
    public function update(Request $request, Tree $tree)
    {
        $validated = $request->validate([
            'id_pohon' => ['required', 'string', 'max:50', 'unique:trees,id_pohon,' . $tree->id],
            'nama_pohon' => ['required', 'string', 'max:150'],
            'nama_latin' => ['nullable', 'string', 'max:150'],
            'diameter' => ['nullable', 'numeric', 'min:0'],
            'tinggi' => ['nullable', 'numeric', 'min:0'],
            'berat_jenis' => ['nullable', 'numeric', 'min:0'],
            'tanggal_tanam' => ['nullable', 'date'],
            'kordinat_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'kordinat_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'estimasi_karbon' => ['required', 'numeric', 'min:0'],
            'lokasi_taman' => ['nullable', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if ($request->hasFile('foto')) {
            if ($tree->foto && Storage::disk('public')->exists($tree->foto)) {
                Storage::disk('public')->delete($tree->foto);
            }
            $fotoPath = $request->file('foto')->store('trees', 'public');
            $validated['foto'] = $fotoPath;
        }

        $tree->update($validated);

        return redirect()->route('trees.show', $tree)
            ->with('success', "Data pohon '{$tree->nama_pohon}' berhasil diperbarui.");
    }

    /**
     * Hapus data pohon beserta aset foto dan QR code.
     */
    public function destroy(Tree $tree)
    {
        $treeName = $tree->nama_pohon;
        $tree->delete(); // Model deleting event handles file cleanup

        return redirect()->route('trees.index')
            ->with('success', "Data pohon '{$treeName}' berhasil dihapus.");
    }

    /**
     * Halaman template cetak QR Code (signage/kartu identitas pohon siap print).
     */
    public function printQr(Tree $tree)
    {
        return view('trees.print-qr', compact('tree'));
    }

    /**
     * Unduh file QR Code dalam format SVG atau raw stream.
     */
    public function downloadQr(Tree $tree, $format = 'svg')
    {
        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($tree->public_url);

        $fileName = 'QR_' . $tree->id_pohon . '.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Regenerate QR Code jika terjadi perubahan atau file hilang.
     */
    public function regenerateQr(Tree $tree)
    {
        $path = $this->qrCodeService->generateAndSave($tree);
        $tree->updateQuietly(['qr_code_path' => $path]);

        return back()->with('success', 'QR Code berhasil dibuat ulang.');
    }
}
