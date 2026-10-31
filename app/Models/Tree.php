<?php

namespace App\Models;

use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tree extends Model
{
    use HasFactory;

    protected $table = 'trees';

    protected $fillable = [
        'id_pohon',
        'nama_pohon',
        'nama_latin',
        'diameter',
        'tinggi',
        'tingkat',
        'berat_jenis',
        'biomassa_agb',
        'cadangan_karbon',
        'tanggal_tanam',
        'kordinat_lat',
        'kordinat_lng',
        'estimasi_karbon',
        'foto',
        'qr_code_path',
        'lokasi_taman',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'diameter' => 'decimal:2',
        'tinggi' => 'decimal:2',
        'berat_jenis' => 'decimal:3',
        'biomassa_agb' => 'decimal:2',
        'cadangan_karbon' => 'decimal:3',
        'estimasi_karbon' => 'decimal:2',
        'kordinat_lat' => 'float',
        'kordinat_lng' => 'float',
    ];

    /**
     * Booted model events to handle QR code generation and asset cleanup automatically.
     */
    protected static function booted(): void
    {
        static::created(function (Tree $tree) {
            if (empty($tree->qr_code_path)) {
                $service = app(QrCodeService::class);
                $path = $service->generateAndSave($tree);
                $tree->updateQuietly(['qr_code_path' => $path]);
            }
        });

        static::updated(function (Tree $tree) {
            // Jika id_pohon berubah, buat ulang QR code
            if ($tree->wasChanged('id_pohon')) {
                $service = app(QrCodeService::class);
                if ($tree->getOriginal('qr_code_path')) {
                    $service->delete($tree->getOriginal('qr_code_path'));
                }
                $path = $service->generateAndSave($tree);
                $tree->updateQuietly(['qr_code_path' => $path]);
            }
        });

        static::deleting(function (Tree $tree) {
            $service = app(QrCodeService::class);
            if ($tree->qr_code_path) {
                $service->delete($tree->qr_code_path);
            }
            if ($tree->foto && Storage::disk('public')->exists($tree->foto)) {
                Storage::disk('public')->delete($tree->foto);
            }
        });
    }

    /**
     * Accessor untuk menghitung umur pohon secara otomatis dari tanggal_tanam.
     * Contoh output: "2 tahun 3 bulan", "8 bulan 14 hari", "5 hari".
     */
    public function getUmurAttribute(): string
    {
        if (!$this->tanggal_tanam) {
            return 'Tidak diketahui';
        }

        $tanam = Carbon::parse($this->tanggal_tanam)->startOfDay();
        $now = now()->startOfDay();

        if ($tanam->isFuture()) {
            return 'Rencana penanaman (' . $tanam->diffForHumans($now) . ')';
        }

        $diff = $tanam->diff($now);
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        $parts = [];
        if ($years > 0) {
            $parts[] = "{$years} tahun";
        }
        if ($months > 0) {
            $parts[] = "{$months} bulan";
        }
        if ($days > 0 && ($years === 0 || $months === 0)) {
            $parts[] = "{$days} hari";
        }

        if (empty($parts)) {
            return 'Baru ditanam hari ini';
        }

        return implode(' ', $parts);
    }

    /**
     * Cek apakah pohon memiliki titik koordinat GPS.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->kordinat_lat) && !is_null($this->kordinat_lng);
    }

    /**
     * URL ke Google Maps berdasarkan koordinat pohon (jika tersedia).
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->hasCoordinates()) {
            return "https://www.google.com/maps?q={$this->kordinat_lat},{$this->kordinat_lng}";
        }
        return null;
    }

    /**
     * URL publik untuk akses detail pohon saat di-scan QR code.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('pohon.public', $this->id_pohon);
    }

    /**
     * Estimasi serapan karbon dalam satuan Ton CO2e.
     */
    public function getEstimasiKarbonTonAttribute(): float
    {
        return round((float)$this->estimasi_karbon / 1000, 4);
    }

    /**
     * URL foto pohon atau placeholder foto botani berkualitas tinggi.
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Foto botani khas hutan tropis & pohon peneduh berdasarkan jenis
        $namaLower = strtolower($this->nama_pohon ?? '');
        if (str_contains($namaLower, 'ulin')) {
            return 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($namaLower, 'meranti') || str_contains($namaLower, 'bengkirai') || str_contains($namaLower, 'kapur')) {
            return 'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($namaLower, 'mahoni')) {
            return 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($namaLower, 'durian') || str_contains($namaLower, 'mangga') || str_contains($namaLower, 'rambutan')) {
            return 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($namaLower, 'tanjung') || str_contains($namaLower, 'bintangur')) {
            return 'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=1200&q=80';
        }

        return 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80';
    }

    /**
     * URL file QR Code (menggunakan rute dinamis agar selalu berhasil dimuat di Vercel/mobile tanpa dependensi storage lokal).
     */
    public function getQrCodeUrlAttribute(): string
    {
        return route('pohon.qrcode.raw', $this->id_pohon);
    }
}
