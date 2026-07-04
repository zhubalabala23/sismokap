<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\BackupLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Display general settings form.
     */
    public function pengaturan()
    {
        $settings = [
            'nama_instansi' => Setting::getValue('nama_instansi', 'SISMOKAP'),
            'logo' => Setting::getValue('logo'),
            'alamat' => Setting::getValue('alamat'),
        ];

        return view('setting.pengaturan', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'alamat' => 'required|string',
        ], [
            'nama_instansi.required' => 'Nama instansi wajib diisi.',
            'alamat.required' => 'Alamat instansi wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo tidak boleh melebihi 2MB.',
        ]);

        Setting::setValue('nama_instansi', $request->nama_instansi);
        Setting::setValue('alamat', $request->alamat);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::getValue('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Upload new logo
            $path = $request->file('logo')->store('settings', 'public');
            Setting::setValue('logo', $path);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Display database backup logs and backup action.
     */
    public function backup()
    {
        $backups = BackupLog::orderBy('created_at', 'desc')->paginate(10);
        return view('setting.backup', compact('backups'));
    }

    /**
     * Trigger database backup process.
     */
    public function runBackup()
    {
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        $filename = 'backup_' . $database . '_' . date('Ymd_His') . '.sql';
        $directory = storage_path('app/backups');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // Path to mysqldump executable in XAMPP
        $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

        if (!File::exists($mysqldumpPath)) {
            return redirect()->back()->with('error', 'Utilitas mysqldump tidak ditemukan di jalur default XAMPP.');
        }

        // Build command safely
        $passwordOption = $password ? '--password=' . escapeshellarg($password) : '';
        
        $command = sprintf(
            '%s --host=%s --user=%s %s %s > %s',
            $mysqldumpPath,
            escapeshellarg($host),
            escapeshellarg($username),
            $passwordOption,
            escapeshellarg($database),
            escapeshellarg($path)
        );
        
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && File::exists($path) && File::size($path) > 0) {
            // Save log
            BackupLog::create([
                'filename' => $filename
            ]);

            return redirect()->back()->with('success', 'Backup database berhasil dibuat.');
        }

        return redirect()->back()->with('error', 'Gagal membuat backup database. Pastikan MySQL berjalan dengan benar.');
    }

    /**
     * Download backup file.
     */
    public function downloadBackup($id)
    {
        $backup = BackupLog::findOrFail($id);
        $path = storage_path('app/backups/' . $backup->filename);

        if (File::exists($path)) {
            return Response::download($path);
        }

        return redirect()->back()->with('error', 'Berkas backup tidak ditemukan di server.');
    }

    /**
     * Delete backup file and its log.
     */
    public function deleteBackup($id)
    {
        $backup = BackupLog::findOrFail($id);
        $path = storage_path('app/backups/' . $backup->filename);

        if (File::exists($path)) {
            File::delete($path);
        }

        $backup->delete();

        return redirect()->back()->with('success', 'Berkas backup berhasil dihapus.');
    }
}
