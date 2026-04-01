<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use LogsAudit;

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);

        $this->logAudit('create', 'user', $user->id, [
            'nip' => $user->nip,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        $updateData = [
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);

        $this->logAudit('update', 'user', $user->id, [
            'nip' => $user->nip,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Tidak dapat menonaktifkan akun sendiri.']);
        }

        $user->update(['is_active' => false]);

        $this->logAudit('deactivate', 'user', $user->id, [
            'nip' => $user->nip,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dinonaktifkan.');
    }
}
