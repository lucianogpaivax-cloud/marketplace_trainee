<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['customer', 'seller', 'admin']);
    }

    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['customer', 'seller', 'admin']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'seller';
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'seller') {
            return $product->id_seller === optional($user->seller)->id_seller;
        }

        return false;
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }
}
