<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductPhotoSeeder extends Seeder
{
    private $allFakerPhotos;
    private $fakerPhotosPath = 'app/faker/product_photos';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->allFakerPhotos = $this->getFakerPhotos();
        $products = Product::all();
        $this->deleteAllPhotosProductsPath();
        $self = $this;
        $products->each(function ($product) use ($self) {
            $self->createPhotoDir($product);
            $self->createPhotosModels($product);
        });
    }

    /**
     * @return Collection
     */
    private function getFakerPhotos(): Collection
    {
        $path = storage_path($this->fakerPhotosPath);
        return collect(\File::allFiles($path));
    }

    /**
     *
     */
    private function deleteAllPhotosProductsPath()
    {
        $path = ProductPhoto::PRODUCTS_PATH;
        \File::deleteDirectory(storage_path($path), true);
    }

    /**
     * @param $product
     */
    private function createPhotoDir($product)
    {
        $path = ProductPhoto::photosPath($product->id);
        \File::makeDirectory($path, 0777, true);
    }

    /**
     * @param Product $product
     */
    private function createPhotosModels(Product $product)
    {
        foreach (range(1, 5) as $v) {
            $this->createPhotoModels($product);
        }
    }

    /**
     * @param Product $product
     */
    private function createPhotoModels(Product $product)
    {
        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'file_name' => 'imagem.jpg'
        ]);
        $this->generatePhoto($photo);
    }

    /**
     * @param ProductPhoto $photo
     */
    private function generatePhoto(ProductPhoto $photo)
    {
        $photo->file_name = $this->uploadPhoto($photo->product_id);
        $photo->save();
    }

    /**
     * @param $product_id
     * @return string
     */
    private function uploadPhoto($product_id): string
    {
        $photoFile = $this->allFakerPhotos->random();
        $uploadFile = new UploadedFile($photoFile->getRealPath(), Str::random(16) . '.' . $photoFile->getExtension());
        ProductPhoto::uploadFiles($product_id, [$uploadFile]);
        return $uploadFile->hashName();
    }
}
