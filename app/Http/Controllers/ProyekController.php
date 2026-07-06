<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Lokasi;
use App\Models\Kontraktor;
use App\Http\Requests\StoreProyekRequest;
use App\Http\Requests\UpdateProyekRequest;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Proyek::with(['lokasi', 'kontraktor']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_proyek', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhere('jenis_pekerjaan', 'like', "%{$search}%")
                  ->orWhereHas('kontraktor', function ($qk) use ($search) {
                      $qk->where('nama_kontraktor', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $proyeks = $query->paginate(15)->withQueryString();

        return view('proyek.index', compact('proyeks', 'search', 'status'));
    }

    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $kontraktors = Kontraktor::orderBy('nama_kontraktor')->get();
        return view('proyek.create', compact('lokasis', 'kontraktors'));
    }

    public function store(StoreProyekRequest $request)
    {
        $validatedData = $request->validated();
        
        $lokasiNama = $validatedData['lokasi_nama'];
        $lokasi = Lokasi::firstOrCreate(
            ['nama_lokasi' => $lokasiNama],
            ['alamat' => $lokasiNama]
        );
        
        $validatedData['lokasi_id'] = $lokasi->id;
        unset($validatedData['lokasi_nama']);

        $pelaksanaNama = $validatedData['pelaksana_nama'];
        $kontraktor = Kontraktor::firstOrCreate(
            ['nama_kontraktor' => $pelaksanaNama],
            ['alamat' => '-']
        );
        
        $validatedData['kontraktor_id'] = $kontraktor->id;
        unset($validatedData['pelaksana_nama']);

        $proyek = Proyek::create($validatedData);

        if ($request->hasFile('foto')) {
            $disk = config('filesystems.default');
            $path = \App\Models\Dokumentasi::uploadAndCompressImage($request->file('foto'), $disk);

            \App\Models\Dokumentasi::create([
                'proyek_id' => $proyek->id,
                'file_path' => $path,
                'tanggal_upload' => now()->format('Y-m-d'),
                'keterangan' => 'Foto kondisi awal proyek ' . $proyek->nama_proyek,
            ]);
        }

        return redirect()->route('admin.proyek.index')
            ->with('success', 'Data proyek berhasil ditambahkan.');
    }

    public function show(Proyek $proyek)
    {
        return redirect()->route('admin.proyek.index');
    }

    public function edit(Proyek $proyek)
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $kontraktors = Kontraktor::orderBy('nama_kontraktor')->get();
        return view('proyek.edit', compact('proyek', 'lokasis', 'kontraktors'));
    }

    public function update(UpdateProyekRequest $request, Proyek $proyek)
    {
        $validatedData = $request->validated();
        
        $lokasiNama = $validatedData['lokasi_nama'];
        
        if ($proyek->lokasi_id) {
            $currentLokasi = $proyek->lokasi;
            if ($currentLokasi && $currentLokasi->nama_lokasi !== $lokasiNama) {
                // Check if another location with the new name already exists
                $existingLokasi = Lokasi::where('nama_lokasi', $lokasiNama)->first();
                
                if ($existingLokasi) {
                    // Associate project with the existing location
                    $validatedData['lokasi_id'] = $existingLokasi->id;
                    $oldLokasi = $currentLokasi;
                } else {
                    // Update the existing location name directly
                    $currentLokasi->update([
                        'nama_lokasi' => $lokasiNama,
                        'alamat' => $lokasiNama
                    ]);
                    $validatedData['lokasi_id'] = $currentLokasi->id;
                }
            } else {
                $validatedData['lokasi_id'] = $proyek->lokasi_id;
            }
        } else {
            // Find or create
            $lokasi = Lokasi::firstOrCreate(
                ['nama_lokasi' => $lokasiNama],
                ['alamat' => $lokasiNama]
            );
            $validatedData['lokasi_id'] = $lokasi->id;
        }
        
        unset($validatedData['lokasi_nama']);

        $pelaksanaNama = $validatedData['pelaksana_nama'];
        if ($proyek->kontraktor_id) {
            $currentKontraktor = $proyek->kontraktor;
            if ($currentKontraktor && $currentKontraktor->nama_kontraktor !== $pelaksanaNama) {
                $existingKontraktor = Kontraktor::where('nama_kontraktor', $pelaksanaNama)->first();
                if ($existingKontraktor) {
                    $validatedData['kontraktor_id'] = $existingKontraktor->id;
                } else {
                    $currentKontraktor->update([
                        'nama_kontraktor' => $pelaksanaNama
                    ]);
                    $validatedData['kontraktor_id'] = $currentKontraktor->id;
                }
            } else {
                $validatedData['kontraktor_id'] = $proyek->kontraktor_id;
            }
        } else {
            $kontraktor = Kontraktor::firstOrCreate(
                ['nama_kontraktor' => $pelaksanaNama],
                ['alamat' => '-']
            );
            $validatedData['kontraktor_id'] = $kontraktor->id;
        }
        unset($validatedData['pelaksana_nama']);

        $proyek->update($validatedData);

        // Handle image upload / edit
        if ($request->hasFile('foto')) {
            $disk = config('filesystems.default');
            $path = \App\Models\Dokumentasi::uploadAndCompressImage($request->file('foto'), $disk);
            
            // Try to find the first/initial documentation for this project
            $initialFoto = $proyek->dokumentasi()->first();
            if ($initialFoto) {
                // Delete old file from storage
                if ($initialFoto->file_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($initialFoto->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk($disk)->delete($initialFoto->file_path);
                }
                // Update file path
                $initialFoto->update([
                    'file_path' => $path,
                    'tanggal_upload' => now()->format('Y-m-d'),
                ]);
            } else {
                // Create a new documentation record
                \App\Models\Dokumentasi::create([
                    'proyek_id' => $proyek->id,
                    'file_path' => $path,
                    'tanggal_upload' => now()->format('Y-m-d'),
                    'keterangan' => 'Foto kondisi awal proyek ' . $proyek->nama_proyek,
                ]);
            }
        }

        // If the old location is now orphaned, delete it to keep database clean
        if (isset($oldLokasi)) {
            $otherProjectsCount = Proyek::where('lokasi_id', $oldLokasi->id)->count();
            if ($otherProjectsCount === 0) {
                $oldLokasi->delete();
            }
        }

        return redirect()->route('admin.proyek.index')
            ->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek)
    {
        try {
            // Hapus berkas foto dari storage (lokal atau Supabase) sebelum menghapus data proyek
            $disk = config('filesystems.default');
            foreach ($proyek->dokumentasi as $foto) {
                if ($foto->file_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($foto->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk($disk)->delete($foto->file_path);
                }
            }

            // Hapus proyek dari database (CASCADE DELETE akan otomatis menghapus baris terkait di tabel lain)
            $proyek->delete();

            return redirect()->route('admin.proyek.index')
                ->with('success', 'Data proyek beserta berkas dokumentasi terkait berhasil dihapus. Kode proyek kini dapat digunakan kembali.');
        } catch (\Exception $e) {
            return redirect()->route('admin.proyek.index')
                ->with('error', 'Gagal menghapus proyek: ' . $e->getMessage());
        }
    }
}
