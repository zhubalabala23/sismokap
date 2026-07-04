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
        $query = Kontraktor::query();

        if ($search) {
            $query->where('nama_kontraktor', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        $kontraktors = $query->paginate(10)->withQueryString();

        return view('kontraktor.index', compact('kontraktors', 'search'));
    }

    public function store(StoreKontraktorRequest $request)
    {
        Kontraktor::create($request->validated());

        return redirect()->route('admin.kontraktor.index')
            ->with('success', 'Data kontraktor berhasil ditambahkan.');
    }

    public function update(UpdateKontraktorRequest $request, Kontraktor $kontraktor)
    {
        $kontraktor->update($request->validated());

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
