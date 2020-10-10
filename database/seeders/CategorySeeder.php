<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
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
        $this->deleteAllPhotosCategoriesPath();
        Category::createWithPhoto([
            'category_name' => 'Laços',
            'slug' => 'lacos',
            'description' => 'Os mais lindos e fofos laços para enfeitar sua criança',
            'photo' => $this->getLacoPhoto()
        ]);
        Category::createWithPhoto([
            'category_name' => 'Tiaras',
            'slug' => 'tiaras',
            'description' => 'As mais lindas e fofas tiaras para enfeitar sua cabeça',
            'photo' => $this->getTiaraPhoto()
        ]);
        Category::createWithPhoto([
            'category_name' => 'Viseiras',
            'slug' => 'viseiras',
            'description' => 'As mais lindas e fofas viseiras para enfeitar sua cabeça',
            'photo' => $this->getViseiraPhoto()
        ]);
        Category::createWithPhoto([
            'category_name' => 'Faixas Para Bebe',
            'slug' => 'faixas-para-bebe',
            'description' => 'As mais lindas e fofas faixas para enfeitar a cabeça de seu bebe',
            'photo' => $this->getUploadedFile()
        ]);
        Category::factory(20)->make()->each(function (Category $category) {
            Category::createWithPhoto($category->toArray() + ['photo' => $this->getUploadedFile()]);
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
    private function deleteAllPhotosCategoriesPath()
    {
        $path = Category::CATEGORIES_PATH;
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

    /**
     * @return UploadedFile
     */
    private function getLacoPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/product_photos/bgntnumymjnimki,imimimiymi.jpg'),
            Str::random(16) . 'jpg'
        );
    }

    /**
     * @return UploadedFile
     */
    private function getFaixaPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/product_photos/btrntynrhgrtgtbrthyrtyjtujujtu.jpg'),
            Str::random(16) . 'jpg'
        );
    }

    /**
     * @return UploadedFile
     */
    private function getTiaraPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/product_photos/btrryhhybryhydtvbbyhrhththyyyfyys.jpg'),
            Str::random(16) . 'jpg'
        );
    }

    /**
     * @return UploadedFile
     */
    private function getViseiraPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/product_photos/btryjukki7kkukyjtyyhyjukikuyukiklikyujns.jpg'),
            Str::random(16) . 'jpg'
        );
    }
}
