<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Jobs\UploadProductImagesToWooCommerce;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use WooCommerce;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductService $productService)
    {
        if ($request->has('datatable')) {
          return  $productService->getDataTableProdcuts($request);
        }

        return Inertia::render('Products/index');
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images' => 'nullable|array',
            'images.0' => 'mimes:jpg,jpeg,png,webp,gif,svg,bmp,ico,tiff,tga,heic,heif,jxl,avif,apng,tiff,tga',
        ]);

        $productData = [
            'name' => $validated['name'],
            'type' => 'simple',
            'regular_price' => (string)$validated['price'],
            'description' => $validated['description'],
        ];

        $product = WooCommerce::post('products', $productData);

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('temp/products', 'local');
                $imagePaths[] = [
                    'path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType()
                ];
            }
            UploadProductImagesToWooCommerce::dispatch($product->id, $imagePaths);
        }
        
        ProductDataTable::clearTotalCache();
        
        return Inertia::render('Products/index' , [
            'message' => [ 'text' => 'Product created successfully' , 'type' => 'success' ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = WooCommerce::get('products/' . $id);
        return Inertia::render('Products/update', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price ?? $product->regular_price,
                'images' => $product->images ?? [],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}