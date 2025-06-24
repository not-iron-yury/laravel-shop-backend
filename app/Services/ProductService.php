<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{




    public function index() {
        return Product::all();
    }

    public function store(array $data) {


        /**
         * Auth::user() — текущий пользователь.
         * products() — метод-связь hasMany из User.
         * create($data) — создаёт товар, автоматически подставляя user_id.
         * Laravel знает, что products() — это связь, и при создании добавит user_id сам. Очень удобно.
         */

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->products()->create($data);
    }

    public function show(Product $product){
        return $product;
    }

    public function update(Product $product, array $data){
        $product->update($data);
        return $product;
    }

    public function delete(Product $product){
        return $product->delete();
    }

}
