<?php

namespace App\Services;

use App\Repositories\Eloquent\ProductRepository;
use App\Services\Api\ImagekitServiceInterface;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductService
{
    protected $productRepository;
    protected $imagekitService;

    public function __construct(
        ProductRepository $productRepository,
        ImagekitServiceInterface $imagekitService,
    ) {
        $this->productRepository = $productRepository;
        $this->imagekitService = $imagekitService;
    }

    /**
     * Get paginated product list.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->productRepository->getPaginated($perPage, $search);
    }

    /**
     * Find a product.
     */
    public function find(int $id)
    {
        return $this->productRepository->find($id);
    }

    /**
     * Save product record and upload its image to ImageKit.
     */
    public function save(array $data, $imageFile = null, ?int $id = null)
    {
        $product = $id ? $this->productRepository->find($id) : null;
        $imageLocalPath = $product ? $product->image : null;
        $imageUrl = $product ? $product->image_url : null;

        if ($imageFile instanceof TemporaryUploadedFile) {
            $filename = createFilename($data['name'], $imageFile->getClientOriginalExtension());

            // Save to local public storage
            $imageLocalPath = $imageFile->storeAs('images/products', $filename, 'public');

            // Delete old image if exist
            if ($product && $product->image && $product->image !== $imageLocalPath) {
                Storage::disk('public')->delete($product->image);
            }
            if ($product && $product->image_url) {
                try {
                    $this->imagekitService->delete($product->image_url);
                } catch (\Exception $e) {
                    logger()->warning(
                        'Failed to delete old image from ImageKit: ' . $e->getMessage(),
                    );
                }
            }

            // Upload new image to ImageKit
            $imageUrl = $this->imagekitService->upload($imageLocalPath, $filename, 'products');
        }

        $saveData = [
            'name' => $data['name'],
            'sku' => $data['sku'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $imageLocalPath,
            'image_url' => $imageUrl,
        ];

        return $this->productRepository->createOrUpdate($saveData, $id);
    }

    /**
     * Get top sales.
     */
    public function getTopSales($startDate, $endDate)
    {
        return $this->productRepository->getTopSales($startDate, $endDate);
    }
}
