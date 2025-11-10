<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserCreateRequest;
use App\Http\Requests\AdminUserRoleUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Correction de l'import

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('id')
            ->paginate(20);

        return response()->json($users);
    }

    public function createAdmin(AdminUserCreateRequest $request) // Correction du nom
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash au lieu de hash
        ]);

        $user->assignRole('admin');

        return response()->json([
            'message' => 'Compte admin créé avec succès',
            'user' => $user->load('roles')
        ], 201);
    }

    public function updateRole(AdminUserRoleUpdateRequest $request, int $id) //  Utiliser le bon Request
    {
        $user = User::findOrFail($id);
        $newRole = $request->role_name;

        // Vérifier qu'on ne modifie pas un super-admin
        if ($user->hasRole('super-admin') && $newRole !== 'super-admin') {
            return response()->json([
                'message' => 'Opération non autorisée sur un Super Admin.'
            ], 403);
        }

        $user->syncRoles([$newRole]); // Mettre en tableau

        return response()->json([
            'message' => "Le rôle de l'utilisateur {$user->name} a été mis à jour à '{$newRole}'.",
            'user' => $user->load('roles')
        ]);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        //  Empêcher de supprimer son propre compte
        if (auth()->user()->id === $id) {
            return response()->json([
                'message' => 'Impossible de supprimer votre propre compte.'
            ], 403);
        }

        // Empêcher de supprimer un super-admin
        if ($user->hasRole('super-admin')) {
            return response()->json([
                'message' => 'Impossible de supprimer un compte Super Admin.'
            ], 403);
        }

        $userName = $user->name;
        $user->delete();

        return response()->json([
            'message' => "L'utilisateur {$userName} a été supprimé."
        ]);
    }
}
