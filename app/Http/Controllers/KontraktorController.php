<?php

namespace App\Http\Controllers;

use App\Models\Kontraktor;
use App\Http\Requests\StoreKontraktorRequest;
use App\Http\Requests\UpdateKontraktorRequest;
use Illuminate\Http\Request;

class KontraktorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Kontraktor::with('proyekAssociated');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kontraktor', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('nama_penanggung_jawab', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_kontrak', 'like', "%{$search}%")
                  ->orWhereHas('proyekAssociated', function ($qp) use ($search) {
                      $qp->where('nama_proyek', 'like', "%{$search}%")
                        ->orWhere('kode_proyek', 'like', "%{$search}%");
                  });
            });
        }

        $kontraktors = $query->paginate(10)->withQueryString();
        $proyeks = \App\Models\Proyek::orderBy('nama_proyek')->get();

        return view('kontraktor.index', compact('kontraktors', 'search', 'proyeks'));
    }

    public function store(StoreKontraktorRequest $request)
    {
        $kontraktor = Kontraktor::create($request->validated());

        if ($request->filled('proyek_id')) {
            $proyek = \App\Models\Proyek::find($request->proyek_id);
            if ($proyek) {
                $proyek->update(['kontraktor_id' => $kontraktor->id]);
            }
        }

        return redirect()->route('admin.kontraktor.index')
            ->with('success', 'Data kontraktor berhasil ditambahkan.');
    }

    public function update(UpdateKontraktorRequest $request, Kontraktor $kontraktor)
    {
        $oldProyekId = $kontraktor->proyek_id;
        $kontraktor->update($request->validated());

        if ($oldProyekId != $kontraktor->proyek_id && $kontraktor->proyek_id) {
            $proyek = \App\Models\Proyek::find($kontraktor->proyek_id);
            if ($proyek) {
                $proyek->update(['kontraktor_id' => $kontraktor->id]);
            }
        } elseif ($kontraktor->proyek_id) {
            $proyek = \App\Models\Proyek::find($kontraktor->proyek_id);
            if ($proyek && $proyek->kontraktor_id != $kontraktor->id) {
                $proyek->update(['kontraktor_id' => $kontraktor->id]);
            }
        }

        return redirect()->route('admin.kontraktor.index')
            ->with('success', 'Data kontraktor berhasil diperbarui.');
    }

    public function destroy(Kontraktor $kontraktor)
    {
        try {
            $kontraktor->delete();
            return redirect()->route('admin.kontraktor.index')
                ->with('success', 'Data kontraktor berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kontraktor.index')
                ->with('error', 'Gagal menghapus kontraktor karena memiliki keterkaitan data dengan proyek.');
        }
    }
}
