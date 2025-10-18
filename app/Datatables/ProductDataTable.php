<?php

namespace App\DataTables;

use App\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables as DataTablesFacade;
use WooCommerce;

class ProductDataTable extends DataTables
{

    public function getData() {
        
        $products = WooCommerce::get('products', $this->params);

        $data = collect($products)->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
        ]); 

        return DataTablesFacade::of($data)
            ->addIndexColumn()
            ->editColumn('name', fn($product) => $product['name'])
            ->editColumn('price', fn($product) => $product['price'])
            ->addColumn('action', fn($product) =>
                '<button class="bg-green-800 px-4 py-1 rounded-[4px]" data-id="'.$product['id'].'">Edit</button>'
            )
            ->rawColumns(['action'])
            ->make(true);
    }
}
