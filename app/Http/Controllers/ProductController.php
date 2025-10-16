<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;
use WooCommerce;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store()
    {
        return Inertia::render('Products/index');
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
    public function edit()
    {
        //
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

    public function dataTable()
    {
        $products = WooCommerce::get('products');
        $collection = collect($products);
        return DataTables::of($collection)
            ->addIndexColumn()
            ->editColumn('name', fn($product) => $product->name)
            ->editColumn('price', fn($product) => $product->price)
            ->editColumn('stock_status', fn($product) => $product->stock_status)
            ->addColumn('action', function ($product) {
                return '<button class="btn btn-sm btn-primary" data-id="'.$product->id.'">Edit</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}