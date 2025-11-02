<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;
use WooCommerce;

class UploadProductImagesToWooCommerce implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout
    public $tries = 3;

    protected $productId;
    protected $imagePaths;

    /**
     * Create a new job instance.
     */
    public function __construct($productId, array $imagePaths)
    {
        $this->productId = $productId;
        $this->imagePaths = $imagePaths;
    }

    public function handle(): void
    {
        $uploadedImages = [];

        $client = new Client([
            'base_uri' => config('woocommerce.store_url', env('WOOCOMMERCE_STORE_URL')),
            'verify' => false,
            'auth' => [
                env('WOOCOMMERCE_ADMIN'),
                env('WOOCOMMERCE_ADMIN_PASSWORD')
            ],
        ]);

        try {
            foreach ($this->imagePaths as $imageData) {
                $fileContent = Storage::disk('local')->get($imageData['path']);
                $response = $client->request('POST', '/wp-json/wp/v2/media', [
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => $fileContent,
                            'filename' => $imageData['original_name'],
                        ],
                    ],
                ]);

                $media = json_decode($response->getBody(), true);
                if (!isset($media['id'])) {
                    throw new \Exception('Media upload failed: ' . json_encode($media));
                }
                $uploadedImages[] = ['id' => $media['id']];
                Storage::disk('local')->delete($imageData['path']);
            }

            WooCommerce::put("products/{$this->productId}", [
                'images' => $uploadedImages,
                'status' => 'publish',
            ]);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error = json_decode($e->getResponse()->getBody(), true);
                Log::error('WooCommerce Media Upload Error', $error);
            } else {
                Log::error('WooCommerce Media Upload Error', $e->getMessage());
            }
        }
    }

}
