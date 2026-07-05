<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

$localPublicPath = storage_path('app/public');
if (!File::exists($localPublicPath)) {
    echo "Folder penyimpanan lokal tidak ditemukan.\n";
    exit;
}

echo "Memindai file lokal untuk diunggah ke Singapura...\n";
$files = File::allFiles($localPublicPath);

foreach ($files as $file) {
    $realPath = str_replace('\\', '/', $file->getRealPath());
    $basePath = str_replace('\\', '/', storage_path('app/public/'));
    
    // Case-insensitive strip of the base path
    $relativePath = preg_replace('/^' . preg_quote($basePath, '/') . '/i', '', $realPath);

    if (strpos($relativePath, '.') === 0 || basename($relativePath) === '.gitignore') {
        continue;
    }

    echo "Mengunggah: " . $relativePath . " ... ";

    try {
        $content = File::get($file->getRealPath());
        $uploaded = Storage::disk('supabase')->put($relativePath, $content);
        echo ($uploaded ? "SUKSES\n" : "GAGAL\n");
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
echo "Selesai sinkronisasi aset ke Singapura!\n";
