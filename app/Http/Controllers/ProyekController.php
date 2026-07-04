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
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $proyeks = $query->paginate(10)->withQueryString();

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
        Proyek::create($request->validated());

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
        $proyek->update($request->validated());

        return redirect()->route('admin.proyek.index')
            ->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek)
    {
        try {
            $proyek->delete();
            return redirect()->route('admin.proyek.index')
                ->with('success', 'Data proyek berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.proyek.index')
                ->with('error', 'Gagal menghapus proyek. Proyek ini mungkin masih memiliki data terkait.');
        }
    }
}
