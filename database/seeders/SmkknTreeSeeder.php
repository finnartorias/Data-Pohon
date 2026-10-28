<?php

namespace Database\Seeders;

use App\Models\Tree;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use SimpleXMLElement;
use ZipArchive;

class SmkknTreeSeeder extends Seeder
{
    /**
     * Run the database seeds from 'data pohon di kawasan SMKKN.xlsx'.
     */
    public function run(): void
    {
        $filePath = base_path('data pohon di kawasan SMKKN.xlsx');

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

        if (count($rows) <= 1) {
            $this->command->warn("Data di excel kosong atau hanya berisi header.");
            return;
        }

        $dataRows = array_slice($rows, 1);
        $total = count($dataRows);
        $this->command->info("Mengimpor {$total} data pohon dari kawasan SMKKN...");

        $qrService = app(QrCodeService::class);

        foreach ($dataRows as $index => $row) {
            $no = (int) ($row['A'] ?? ($index + 1));
            $namaJenis = trim($row['B'] ?? 'Pohon Tropis');
            $namaLatin = trim($row['C'] ?? '');
            $diameter = isset($row['D']) && is_numeric($row['D']) ? (float) $row['D'] : 15.0;
            $tingkat = trim($row['E'] ?? 'Pohon');
            $beratJenis = isset($row['F']) && is_numeric($row['F']) ? (float) $row['F'] : 0.60;
            $agbKg = isset($row['G']) && is_numeric($row['G']) ? (float) $row['G'] : 0.0;
            $cadanganKarbon = isset($row['I']) && is_numeric($row['I']) ? (float) $row['I'] : 0.0;
            $serapanKarbonTon = isset($row['J']) && is_numeric($row['J']) ? (float) $row['J'] : 0.0;

            // Estimasi serapan karbon dalam kg (1 ton = 1000 kg)
            $estimasiKarbonKg = round($serapanKarbonTon * 1000, 2);

            // Kode unik pohon SMKKN
            $idPohon = 'SMKKN-' . str_pad($no, 3, '0', STR_PAD_LEFT);

            // Perhitungan perkiraan umur pohon berdasarkan diameter dan tingkat pertumbuhan
            $now = Carbon::now();
            if ($tingkat === 'Pancang' || $diameter < 10) {
                $plantingDate = $now->copy()->subYears(1 + ($no % 2))->subMonths($no % 11)->toDateString();
            } elseif ($tingkat === 'Tiang' || ($diameter >= 10 && $diameter < 20)) {
                $plantingDate = $now->copy()->subYears(3 + ($no % 4))->subMonths(($no * 3) % 11)->toDateString();
            } elseif ($diameter >= 20 && $diameter < 40) {
                $plantingDate = $now->copy()->subYears(8 + ($no % 7))->subMonths(($no * 5) % 11)->toDateString();
            } elseif ($diameter >= 40 && $diameter < 60) {
                $plantingDate = $now->copy()->subYears(18 + ($no % 8))->subMonths(($no * 2) % 11)->toDateString();
            } else {
                // Pohon tua/besar diameter > 60 cm (misal Ulin, Nyamplung, Bengkirai besar)
                $plantingDate = $now->copy()->subYears(28 + ($no % 15))->subMonths($no % 11)->toDateString();
            }

            // Koordinat sebaran di kawasan Arboretum SMKKN (Samarinda, Kalimantan Timur)
            $baseLat = -0.4862000;
            $baseLng = 117.1578000;
            $rowBlock = (int) (($no - 1) / 25);
            $colIndex = ($no - 1) % 25;

            $lat = $baseLat + ($colIndex - 12) * 0.00016 + $rowBlock * 0.00008;
            $lng = $baseLng + ($rowBlock - 5) * 0.00018 + $colIndex * 0.00005;

            $blokHuruf = chr(65 + ($rowBlock % 6)); // Blok A sampai F
            $lokasiTaman = "Kawasan Hutan Arboretum SMKKN (Blok {$blokHuruf})";

            $deskripsi = "Pohon {$namaJenis} ({$namaLatin}) kategori {$tingkat} dengan diameter batang {$diameter} cm. "
                . "Memiliki Biomassa Atas Tanah (AGB) " . number_format($agbKg, 2) . " kg dan cadangan karbon "
                . number_format($cadanganKarbon, 3) . " Ton C dalam kawasan konservasi hutan SMKKN.";

            $tree = Tree::updateOrCreate(
                ['id_pohon' => $idPohon],
                [
                    'nama_pohon' => $namaJenis,
                    'nama_latin' => $namaLatin ?: null,
                    'diameter' => $diameter,
                    'tingkat' => $tingkat,
                    'berat_jenis' => $beratJenis,
                    'biomassa_agb' => $agbKg,
                    'cadangan_karbon' => $cadanganKarbon,
                    'estimasi_karbon' => $estimasiKarbonKg,
                    'tanggal_tanam' => $plantingDate,
                    'kordinat_lat' => round($lat, 7),
                    'kordinat_lng' => round($lng, 7),
                    'lokasi_taman' => $lokasiTaman,
                    'deskripsi' => $deskripsi,
                ]
            );

            // Generate QR Code jika belum ada
            if (empty($tree->qr_code_path)) {
                $path = $qrService->generateAndSave($tree);
                $tree->updateQuietly(['qr_code_path' => $path]);
            }
        }

        $this->command->info("Berhasil mengimpor {$total} data pohon kawasan SMKKN beserta kode QR!");
    }
}
