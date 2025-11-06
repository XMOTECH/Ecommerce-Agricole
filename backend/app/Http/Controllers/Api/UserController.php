<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Lister les utilisateurs (admin seulement)",
     *     security={{{"sanctum": {}}}},
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Seuls les administrateurs peuvent voir la liste des utilisateurs
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with('roles')->paginate(20);

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/users/{user}",
     *     tags={"Users"},
     *     summary="Afficher un utilisateur",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(User $user, Request $request): JsonResponse
    {
        $currentUser = $request->user();

        // Les utilisateurs peuvent voir leur propre profil, les admins peuvent voir tous
        if (!$currentUser->hasRole('administrateur') && $currentUser->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($user->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/users/{user}",
     *     tags={"Users"},
     *     summary="Mettre à jour un utilisateur",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        // Les utilisateurs peuvent modifier leur propre profil, les admins peuvent modifier tous
        if (!$currentUser->hasRole('administrateur') && $currentUser->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user->load('roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/users/{user}",
     *     tags={"Users"},
     *     summary="Supprimer un utilisateur (admin seulement)",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Supprimé"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(User $user, Request $request): JsonResponse
    {
        $currentUser = $request->user();

        // Seuls les administrateurs peuvent supprimer des utilisateurs
        if (!$currentUser->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Empêcher la suppression de soi-même
        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'Cannot delete yourself'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Update user role (admin only)
     *
     * @OA\Put(
     *     path="/users/{user}/role",
     *     tags={"Users"},
     *     summary="Changer le rôle d'un utilisateur (admin seulement)",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", enum={"client","administrateur"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function updateRole(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        // Seuls les administrateurs peuvent changer les rôles
        if (!$currentUser->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'role' => 'required|in:client,administrateur',
        ]);

        $user->syncRoles([$validated['role']]);

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user->load('roles')
        ]);
    }
}