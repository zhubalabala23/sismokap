<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

$oldBaseUrl = "https://nljeywstuhhbfyaewokx.supabase.co/storage/v1/object/public/sismokap-dokumentasi/";

// 1. Get all file paths from database
$filesToMigrate = [];

// Get from settings table (logo)
$logoPath = Setting::getValue('logo');
if ($logoPath) {
    $filesToMigrate[] = $logoPath;
}

// Get from dokumentasi table
$docs = DB::table('dokumentasi')->get();
foreach ($docs as $doc) {
    if (!empty($doc->file_path)) {
        $filesToMigrate[] = $doc->file_path;
    }
    if (!empty($doc->video_path)) {
        $filesToMigrate[] = $doc->video_path;
    }
}

$filesToMigrate = array_unique($filesToMigrate);

echo "Ditemukan " . count($filesToMigrate) . " file media untuk dimigrasikan dari Jepang ke Singapura.\n\n";

foreach ($filesToMigrate as $filePath) {
    $oldUrl = $oldBaseUrl . $filePath;
    echo "Memproses: " . $filePath . "\n";
    echo "  - Mengunduh dari Jepang: " . $oldUrl . " ... ";

    try {
        $fileContents = @file_get_contents($oldUrl);
        if ($fileContents === false) {
            echo "GAGAL (File tidak ditemukan di server lama atau bukan URL publik)\n";
            continue;
        }
        echo "SUKSES\n";

        // Save local copy
        $localPath = storage_path('app/public/' . $filePath);
        $localDir = dirname($localPath);
        if (!File::exists($localDir)) {
            File::makeDirectory($localDir, 0755, true, true);
        }
        File::put($localPath, $fileContents);
        echo "  - Menyimpan lokal: OK\n";

        // Upload to new Singapore storage
        echo "  - Mengunggah ke Singapura ... ";
        $uploaded = Storage::disk('supabase')->put($filePath, $fileContents);
        echo ($uploaded ? "SUKSES\n" : "GAGAL\n");

    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "Migrasi media selesai!\n";
