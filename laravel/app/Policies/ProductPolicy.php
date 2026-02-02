<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Product $product)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    public function delete(User $user, Product $product)
    {
        return $user->id === $product->user_id && $product->status !== 'paired';
    }

    public function pay(User $user, Product $product)
    {
        return $user->id === $product->user_id && 
               $product->status === 'pending' && 
               !$product->published;
    }
}