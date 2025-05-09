<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        DB::transaction(function () use ($request) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'is_admin' => false,
            ]);
        });

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $data = $request->only(['name', 'email', 'status']);
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);
        });

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->tasks()->exists()) {
            return redirect()->route('users.index')->with('error', 'Não é possível excluir um usuário com tarefas.');
        }

        DB::transaction(fn() => $user->delete());

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
