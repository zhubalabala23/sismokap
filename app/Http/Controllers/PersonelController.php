<?php

namespace App\Http\Controllers;

use App\Models\Personel;
use App\Http\Requests\StorePersonelRequest;
use App\Http\Requests\UpdatePersonelRequest;
use Illuminate\Http\Request;

class PersonelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Personel::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
        }

        $personels = $query->paginate(10)->withQueryString();

        return view('personel.index', compact('personels', 'search'));
    }

    public function store(StorePersonelRequest $request)
    {
        Personel::create($request->validated());

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil ditambahkan.');
    }

    public function update(UpdatePersonelRequest $request, Personel $personel)
    {
        $personel->update($request->validated());

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil diperbarui.');
    }

    public function destroy(Personel $personel)
    {
        $personel->delete();

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil dihapus.');
    }
}
