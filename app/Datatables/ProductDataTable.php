<?php

namespace App\DataTables;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables as DataTablesFacade;
use WooCommerce;

class ProductDataTable extends DataTablesTable
{
    public const CACHE_KEY_TOTAL_PRODUCTS = 'woocommerce.total.products';

    public function getData(Request $request) {
        
        $products = WooCommerce::get('products', $this->prepareRequest($request));

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
                '<a href="/products/' . $product['id'] . '/edit" class="bg-green-800 px-4 py-1 rounded-[4px] text-white dark:text-black">Edit</a>'
            )
            ->skipPaging()
            ->setFilteredRecords($this->getTotal())
            ->setTotalRecords($this->getTotal())
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Retrieves the total number of products from WooCommerce.
     *
     * This method uses Guzzle to make a GET request to the WooCommerce API
     * and retrieves the total number of products from the 'X-WP-Total' header.
     * The result is cached for 1 day to prevent excessive API requests.
     *
     * @return int
     */
    private function getTotal() : int {
        $key = self::CACHE_KEY_TOTAL_PRODUCTS;
        $total = cache()->remember($key, now()->addDays(1), function () {
            $client = new Client([
                'base_uri' => config('woocommerce.store_url', env('WOOCOMMERCE_STORE_URL')) .'/wp-json/wc/v2/products'
                .'?consumer_key=' . config('woocommerce.consumer_key', env('WOOCOMMERCE_CONSUMER_KEY')) . '&consumer_secret=' . config('woocommerce.consumer_secret', env('WOOCOMMERCE_CONSUMER_SECRET')),
                'verify' => false,
            ]);
            $response = $client->request('GET');
            return (int) $response->getHeader('X-WP-Total')[0];
        });
        return $total;
    }

    public static function clearTotalCache() : void {
        cache()->forget(self::CACHE_KEY_TOTAL_PRODUCTS);
    }
}
