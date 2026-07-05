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
        // Dynamically add password column if it does not exist
        if (!\Illuminate\Support\Facades\Schema::hasColumn('personel', 'password')) {
            \Illuminate\Support\Facades\Schema::table('personel', function ($table) {
                $table->string('password')->nullable();
            });
        }

        // Delete dummy personnel if present
        Personel::whereIn('nama', ['Budi Santoso', 'Siti Aminah', 'Ahmad Hidayat', 'Dewi Sartika'])
            ->whereNull('nrp_nip')
            ->delete();

        $search = $request->input('search');
        $query = Personel::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nrp_nip', 'like', "%{$search}%")
                  ->orWhere('pangkat_golongan', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%")
                  ->orWhere('hak_akses', 'like', "%{$search}%");
        }

        $personels = $query->paginate(10)->withQueryString();

        return view('personel.index', compact('personels', 'search'));
    }

    public function store(StorePersonelRequest $request)
    {
        $validated = $request->validated();
        
        $personel = Personel::create($validated);

        // If email and hak_akses are provided, create corresponding user
        if (!empty($validated['email']) && !empty($validated['hak_akses'])) {
            \App\Models\User::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['nama'],
                    'role' => $validated['hak_akses'],
                    'password' => bcrypt($validated['password'] ?? 'password123'),
                ]
            );
        }

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil ditambahkan.');
    }

    public function update(UpdatePersonelRequest $request, Personel $personel)
    {
        $validated = $request->validated();
        
        $oldEmail = $personel->email;
        
        $personelData = $validated;
        if (empty($personelData['password'])) {
            unset($personelData['password']);
        }
        $personel->update($personelData);

        // Sync with User
        if (!empty($validated['email']) && !empty($validated['hak_akses'])) {
            // Find user by old email first, or update/create by new email
            $user = \App\Models\User::where('email', $oldEmail)->first();
            
            $userData = [
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'role' => $validated['hak_akses'],
            ];
            
            if (!empty($validated['password'])) {
                $userData['password'] = bcrypt($validated['password']);
            }
            
            if ($user) {
                $user->update($userData);
            } else {
                if (empty($userData['password'])) {
                    $userData['password'] = bcrypt('password123');
                }
                \App\Models\User::create($userData);
            }
        } else {
            // If email or hak_akses is removed, delete corresponding user if they existed
            if ($oldEmail) {
                \App\Models\User::where('email', $oldEmail)->delete();
            }
        }

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil diperbarui.');
    }

    public function destroy(Personel $personel)
    {
        $email = $personel->email;
        $personel->delete();

        if ($email) {
            \App\Models\User::where('email', $email)->delete();
        }

        return redirect()->route('admin.personel.index')
            ->with('success', 'Data personel berhasil dihapus.');
    }
}
