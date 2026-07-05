<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Dynamically add password_plain column if it does not exist
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'password_plain')) {
            \Illuminate\Support\Facades\Schema::table('users', function ($table) {
                $table->string('password_plain')->nullable();
            });
        }

        $search = $request->input('search');
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
        }

        $users = $query->paginate(10)->withQueryString();

        return view('user.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password_plain'] = $data['password'];
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Sync to Personel if exists
        $personel = \App\Models\Personel::where('email', $user->email)->first();
        if ($personel) {
            $personel->update([
                'nama' => $user->name,
                'hak_akses' => $user->role,
                'password' => $data['password_plain'],
            ]);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $oldEmail = $user->email;
        
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_plain'] = $data['password'];
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Sync to Personel if exists
        $personel = \App\Models\Personel::where('email', $oldEmail)
            ->orWhere('email', $user->email)
            ->first();
        if ($personel) {
            $personelData = [
                'nama' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'hak_akses' => $user->role,
            ];
            if (isset($data['password_plain'])) {
                $personelData['password'] = $data['password_plain'];
            }
            $personel->update($personelData);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $email = $user->email;
        $user->delete();

        // Sync to Personel (remove hak_akses and password from Personel)
        $personel = \App\Models\Personel::where('email', $email)->first();
        if ($personel) {
            $personel->update([
                'hak_akses' => null,
                'password' => null,
            ]);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
