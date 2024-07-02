<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::with('roles')->orderBy('id', 'desc')->paginate(10);
        return Inertia::render('User/Index', compact('users'));
    }


    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'password' => Hash::make('admin'),
            ]);
            $user->assignRole('admin');

            return redirect()->route('usuarios.index')->with('toast', ['Usuario creado exitosamente!', 'success']);
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(UserRequest $request, User $usuario)
    {
        try {
            $usuario->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
            ]);

            $usuario->syncRoles('admin');

            return redirect()->route('usuarios.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el usuario!', 'danger']);
        }
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('usuarios.index')->with('toast', ['Usuario eliminado exitosamente!', 'success']);
    }
}
