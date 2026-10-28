<?php

namespace App\Services;

use App\Models\Tree;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR Code for a given tree and save to public storage.
     */
    public function generateAndSave(Tree $tree): string
    {
        $publicUrl = route('pohon.public', $tree->id_pohon);

        // Pastikan folder storage qrcodes ada
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        $filename = 'qrcodes/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $tree->id_pohon) . '.svg';

        // Generate QR Code SVG dengan resolusi tajam & error correction tinggi
        $svg = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($publicUrl);

        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }

    /**
     * Delete QR Code file from storage.
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
