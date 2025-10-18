<?php
namespace App\Services;

use App\DataTables\ProductDataTable;
use Illuminate\Http\Request;

class ProductService
{

    public function __construct(protected ProductDataTable $productDataTable ) {

    }

    public function getDataTableProdcuts(Request $request)
    {
        return $this->productDataTable->getData($request);
    }
}