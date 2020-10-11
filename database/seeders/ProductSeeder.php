<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
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
        $colors = Color::all();
        $materials = Material::all();
        $this->allFakerPhotos = $this->getFakerPhotos();
        $this->deleteAllPhotosProductsPath();
        Product::factory(100)
            ->make()
            ->each(function (Product $product) use ($colors, $materials) {
                $product = Product::createWithPhoto($product->toArray() + [
                        'photo' => $this->getUploadedFile()
                    ]);
                for ($i = 1; $i < 4; $i++) {
                    $colorId = $colors->random()->id;
                    $materialId = $materials->random()->id;
                    $product->colors()->attach($colorId);
                    $product->materials()->attach($materialId);
                }
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
        $path = Product::PRODUCTS_PATH;
        \File::deleteDirectory(storage_path($path), true);
    }

    /**
     * @return UploadedFile
     */
    private function getUploadedFile(): UploadedFile
    {
        $photoFile = $this->allFakerPhotos->random();
        $uploadFile = new UploadedFile($photoFile->getRealPath(), Str::random(16) . '.' . $photoFile->getExtension());
        return $uploadFile;
    }
}
