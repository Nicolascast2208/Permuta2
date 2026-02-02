<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class MyProducts extends Component
{
    use WithPagination;

    public function delete(Product $product)
    {
        $product->delete();
        session()->flash('success', 'Producto eliminado');
    }

    public function render()
    {
        $products = auth()->user()->products()->paginate(10);
        return view('livewire.my-products', compact('products'));
    }
}