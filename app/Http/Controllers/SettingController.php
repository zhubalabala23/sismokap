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
            $disk = config('filesystems.default');
            if ($oldLogo && Storage::disk($disk)->exists($oldLogo)) {
                Storage::disk($disk)->delete($oldLogo);
            }

            // Upload new logo
            $path = $request->file('logo')->store('settings', $disk);
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
        $connection = config('database.default');
        $databaseName = config("database.connections.{$connection}.database", 'postgres');
        $filename = 'backup_' . $databaseName . '_' . date('Ymd_His') . '.sql';
        $disk = config('filesystems.default');
        $filePath = 'backups/' . $filename;

        try {
            if ($connection === 'pgsql') {
                $tables = \Illuminate\Support\Facades\DB::select("
                    SELECT table_name 
                    FROM information_schema.tables 
                    WHERE table_schema = 'public' 
                      AND table_type = 'BASE TABLE'
                ");
                
                $sqlDump = "-- SISMOKAP PostgreSQL Database Backup\n";
                $sqlDump .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
                $sqlDump .= "SET statement_timeout = 0;\n";
                $sqlDump .= "SET lock_timeout = 0;\n";
                $sqlDump .= "SET client_encoding = 'UTF8';\n";
                $sqlDump .= "SET standard_conforming_strings = on;\n";
                $sqlDump .= "SET check_function_bodies = false;\n";
                $sqlDump .= "SET xmloption = content;\n";
                $sqlDump .= "SET client_min_messages = warning;\n\n";
                $sqlDump .= "SET CONSTRAINTS ALL DEFERRED;\n\n";

                $tableList = array_map(function($t) { return $t->table_name; }, $tables);

                foreach ($tableList as $table) {
                    if ($table === 'spatial_ref_sys') {
                        continue;
                    }

                    $sqlDump .= "--\n-- Data for Name: $table; Type: TABLE DATA\n--\n\n";
                    $sqlDump .= "TRUNCATE TABLE \"$table\" CASCADE;\n\n";

                    $rows = \Illuminate\Support\Facades\DB::table($table)->get();
                    if ($rows->count() > 0) {
                        foreach ($rows as $row) {
                            $rowArray = (array)$row;
                            $columns = array_keys($rowArray);
                            
                            $escapedColumns = array_map(function($col) {
                                return "\"$col\"";
                            }, $columns);

                            $escapedValues = array_map(function($val) {
                                if ($val === null) {
                                    return 'NULL';
                                }
                                if (is_bool($val)) {
                                    return $val ? 'true' : 'false';
                                }
                                if (is_numeric($val) && !is_string($val)) {
                                    return $val;
                                }
                                return "'" . str_replace("'", "''", $val) . "'";
                            }, array_values($rowArray));

                            $sqlDump .= "INSERT INTO \"$table\" (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                        }
                        $sqlDump .= "\n";
                    }
                }

                \Illuminate\Support\Facades\Storage::disk($disk)->put($filePath, $sqlDump);
            } else {
                return redirect()->back()->with('error', 'Hanya database PostgreSQL yang didukung untuk pencadangan cloud saat ini.');
            }

            if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($filePath)) {
                BackupLog::create([
                    'filename' => $filename
                ]);
                return redirect()->back()->with('success', 'Backup database berhasil dibuat.');
            }
            
            return redirect()->back()->with('error', 'Gagal membuat backup database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat backup database: ' . $e->getMessage());
        }
    }

    /**
     * Download backup file.
     */
    public function downloadBackup($id)
    {
        $backup = BackupLog::findOrFail($id);
        $disk = config('filesystems.default');
        $filePath = 'backups/' . $backup->filename;

        if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($filePath)) {
            return \Illuminate\Support\Facades\Storage::disk($disk)->download($filePath);
        }

        return redirect()->back()->with('error', 'Berkas backup tidak ditemukan di storage cloud.');
    }

    /**
     * Delete backup file and its log.
     */
    public function deleteBackup($id)
    {
        $backup = BackupLog::findOrFail($id);
        $disk = config('filesystems.default');
        $filePath = 'backups/' . $backup->filename;

        if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($filePath)) {
            \Illuminate\Support\Facades\Storage::disk($disk)->delete($filePath);
        }

        $backup->delete();

        return redirect()->back()->with('success', 'Berkas backup berhasil dihapus.');
    }
}
