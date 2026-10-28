<?php

namespace Database\Seeders;

use App\Models\Tree;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use ZipArchive;

class SekolahTreeSeeder extends Seeder
{
    /**
     * Run the database seeds from 'data pengukuran karbon di sekolah.xlsx'.
     */
    public function run(): void
    {
        $filePath = base_path('data pengukuran karbon di sekolah.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File '{$filePath}' tidak ditemukan.");
            return;
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            $this->command->error("Gagal membuka file excel {$filePath}.");
            return;
        }

        // 1. Baca shared strings
        $sharedStrings = [];
        $sharedXmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXmlContent) {
            $xml = new SimpleXMLElement($sharedXmlContent);
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } else {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // 2. Baca sheet1
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXmlContent) {
            $this->command->error("Sheet1 tidak ditemukan di dalam file excel.");
            return;
        }

        $sheetXml = new SimpleXMLElement($sheetXmlContent);
        $rows = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $rowCells = [];
            foreach ($row->c as $c) {
                $ref = (string) $c['r'];
                $col = preg_replace('/[0-9]/', '', $ref);
                $type = (string) $c['t'];
                $val = (string) $c->v;

                if ($type === 's' && isset($sharedStrings[(int) $val])) {
                    $val = $sharedStrings[(int) $val];
                }
                $rowCells[$col] = $val;
            }
            $rows[] = $rowCells;
        }

        // Filter baris data yang valid (Nama Jenis tidak boleh kosong)
        $dataRows = [];
        foreach (array_slice($rows, 1) as $row) {
            $nama = trim($row['B'] ?? '');
            if ($nama !== '') {
                $dataRows[] = $row;
            }
        }

        $total = count($dataRows);
        $this->command->info("Mengimpor {$total} data pohon pengukuran karbon di sekolah (tanpa GPS)...");

        // 3. Bersihkan data pohon lama dan direktori QR code lama
        Tree::query()->delete();
        Storage::disk('public')->deleteDirectory('qrcodes');
        Storage::disk('public')->makeDirectory('qrcodes');

        $qrService = app(QrCodeService::class);
        $now = Carbon::now();

        foreach ($dataRows as $index => $row) {
            $noRaw = trim($row['A'] ?? ($index + 1));
            $no = (int) $noRaw;
            $namaJenis = trim($row['B'] ?? 'Pohon Tropis');
            $namaLatin = trim($row['C'] ?? '');
            $diameter = isset($row['D']) && is_numeric($row['D']) ? (float) $row['D'] : 15.0;
            $tinggi = isset($row['E']) && is_numeric($row['E']) ? (float) $row['E'] : null;
            $beratJenis = isset($row['F']) && is_numeric($row['F']) ? (float) $row['F'] : 0.60;
            $agbKg = isset($row['G']) && is_numeric($row['G']) ? (float) $row['G'] : 0.0;
            $cadanganKarbon = isset($row['I']) && is_numeric($row['I']) ? (float) $row['I'] : 0.0;
            $serapanKarbonTon = isset($row['J']) && is_numeric($row['J']) ? (float) $row['J'] : 0.0;

            // Normalisasi data Sungkai jika berat jenis terinput 588 (skala kg/m3 alih-alih g/cm3 0.588)
            if ($beratJenis > 10) {
                $beratJenis = round($beratJenis / 1000, 3);
                $agbKg = round($agbKg / 1000, 2);
                $cadanganKarbon = round($cadanganKarbon / 1000, 3);
                $serapanKarbonTon = $serapanKarbonTon / 1000;
            }

            // Estimasi serapan karbon dalam kg (1 ton = 1000 kg)
            $estimasiKarbonKg = round($serapanKarbonTon * 1000, 2);

            // Klasifikasi silvikultur tingkat pertumbuhan berdasarkan diameter batang
            if ($diameter < 10) {
                $tingkat = 'Pancang';
                $plantingDate = $now->copy()->subYears(1 + ($no % 2))->subMonths($no % 11)->toDateString();
            } elseif ($diameter < 20) {
                $tingkat = 'Tiang';
                $plantingDate = $now->copy()->subYears(3 + ($no % 4))->subMonths(($no * 3) % 11)->toDateString();
            } else {
                $tingkat = 'Pohon';
                $plantingDate = $now->copy()->subYears(8 + ($no % 7))->subMonths(($no * 5) % 11)->toDateString();
            }

            // Kode identitas pohon
            $idPohon = 'TREE-' . str_pad($no, 3, '0', STR_PAD_LEFT);
            $lokasiTaman = 'Area Lingkungan Sekolah SMKKN';

            $deskripsi = "Pohon {$namaJenis}" . ($namaLatin ? " ({$namaLatin})" : '') . " kategori {$tingkat} dengan diameter batang {$diameter} cm"
                . ($tinggi ? " dan tinggi {$tinggi} m." : '.')
                . " Memiliki Biomassa Atas Tanah (AGB) " . number_format($agbKg, 2) . " kg dan cadangan karbon "
                . number_format($cadanganKarbon, 3) . " Ton C dalam lingkungan sekolah SMKKN.";

            $tree = Tree::create([
                'id_pohon' => $idPohon,
                'nama_pohon' => $namaJenis,
                'nama_latin' => $namaLatin ?: null,
                'diameter' => $diameter,
                'tinggi' => $tinggi,
                'tingkat' => $tingkat,
                'berat_jenis' => $beratJenis,
                'biomassa_agb' => $agbKg,
                'cadangan_karbon' => $cadanganKarbon,
                'estimasi_karbon' => $estimasiKarbonKg,
                'tanggal_tanam' => $plantingDate,
                'kordinat_lat' => null,
                'kordinat_lng' => null,
                'lokasi_taman' => $lokasiTaman,
                'deskripsi' => $deskripsi,
            ]);

            // Pastikan QR code terbentuk
            if (empty($tree->qr_code_path)) {
                $path = $qrService->generateAndSave($tree);
                $tree->updateQuietly(['qr_code_path' => $path]);
            }
        }

        $this->command->info("Berhasil mengimpor {$total} data pohon baru dari sekolah (tanpa GPS) beserta kode QR!");
    }
}

