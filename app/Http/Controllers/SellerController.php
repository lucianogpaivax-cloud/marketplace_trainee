<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    /**
     * Lista todos os seller (rota pública / admin)
     */
    public function index()
    {
        return response()->json(
            Seller::paginate(10),
            200
        );
    }

    /**
     * Mostra um seller específico (rota pública / admin)
     */
    public function show($id)
    {
        $seller = Seller::find($id);

        if (!$seller) {
            return response()->json(['message' => 'seller não encontrado.'], 404);
        }

        return response()->json($seller, 200);
    }

    /**
     * Retorna o perfil do seller logado
     */
    public function getSeller()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $seller = Seller::where('id_user', $user->id_user)->first();

        return response()->json([
            'user'   => $user,
            'seller' => $seller
        ]);
    }

    /**
     * Atualiza perfil completo do seller logado (USER + SELLER)
     */
    public function updateSeller(Request $request)
    {
        $user = Auth::user();
        $user = User::where('id_user', $user->id_user)->first();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $request->validate([
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $user->id_user . ',id_user',
            'telefone'  => 'nullable|string|max:20',
            'nome_loja' => 'nullable|string|max:255',
            'tipo_loja' => 'nullable|string|max:255',
            'origem'    => 'nullable|string|max:255',
        ]);

        // Atualiza USER
        $user->update([
            'name'     => $request->name     ?? $user->name,
            'email'    => $request->email    ?? $user->email,
            'telefone' => $request->telefone ?? $user->telefone,
        ]);

        // Atualiza SELLER
        $seller = Seller::where('id_user', $user->id_user)->first();

        if ($seller) {
            $seller->update([
                'nome_loja' => $request->nome_loja ?? $seller->nome_loja,
                'tipo_loja' => $request->tipo_loja ?? $seller->tipo_loja,
                'origem'    => $request->origem    ?? $seller->origem,
            ]);
        }

        return response()->json([
            'message' => 'Perfil do seller atualizado com sucesso!',
            'user'    => $user,
            'seller'  => $seller
        ]);
    }

    /**
     * Cria perfil de seller (caso ainda não exista)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $request->validate([
            'nome_loja' => 'required|string|max:255',
            'tipo_loja' => 'required|string|max:255',
            'origem'    => 'required|string|max:255',
        ]);

        $existing = Seller::where('id_user', $user->id_user)->first();
        if ($existing) {
            return response()->json([
                'message' => 'O usuário já possui um perfil de seller.'
            ], 409);
        }

        $seller = Seller::create([
            'id_user'   => $user->id_user,
            'nome_loja' => $request->nome_loja,
            'tipo_loja' => $request->tipo_loja,
            'origem'    => $request->origem,
        ]);

        return response()->json([
            'message' => 'Perfil de seller criado com sucesso!',
            'seller'  => $seller
        ], 201);
    }

    /**
     * Deleta o perfil do seller logado
     */
    public function destroy()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        $seller = Seller::where('id_user', $user->id_user)->first();

        if (!$seller) {
            return response()->json(['message' => 'Perfil de seller não encontrado.'], 404);
        }

        $seller->delete();

        return response()->json([
            'message' => 'Perfil de seller excluído com sucesso.'
        ]);
    }
}
