<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $path = storage_path(config('app.products-path', '/app/products.json'));

        if (!file_exists($path)) {
            file_put_contents($path, '[]');
        }

        $products = $this->fetchProducts();

        return $this->sortProducts($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $request->validate([
            'name' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $product['quantity'] = (int) $product['quantity'];
        $product['price'] = (int) $product['price'];

        $product['submitted'] = now()->toDateTimeString();

        $products = $this->fetchProducts();

        $products[] = $product;

        $this->saveProducts($products);

        return $product;
    }

    protected function fetchProducts($path = null)
    {
        $path = $path ?: config('app.products-path', storage_path('/app/products.json'));

        return json_decode(file_get_contents($path), true);
    }

    protected function saveProducts($products, $path = null)
    {
        $path = $path ?: config('app.products-path', storage_path('/app/products.json'));

        file_put_contents($path, json_encode($products));

        return $this;
    }

    protected function sortProducts($products, $key = 'submitted')
    {
        return collect($products)->sortByDesc(function ($product) use ($key) {
            return Carbon::parse($product[$key])->timestamp;
        })->values()->all();
    }
}
