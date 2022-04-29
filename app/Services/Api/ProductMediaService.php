<?php

namespace App\Services\Api;

use App\Repositories\ProductMediaRepository;
use App\Services\FileService;

class ProductMediaService
{
    public function __construct(
        private ProductMediaRepository $productMediaRepository,
        private FileService $fileService
    ) {
    }

    public function upload($productID, $files)
    {
        $medias = [];
        $files = is_array($files) ? $files : [$files];
        foreach ($files as $file) {
            $saved = $this->fileService->uploadFile($file, '/uploads/medias');
            $medias[] = $this->store($productID, $saved['path']);
        }
        return $medias;
    }

    private function store($productID, string $path)
    {
        return $this->productMediaRepository->create([
            'url'               => $path,
            'product_id' => $productID
        ]);
    }
}
