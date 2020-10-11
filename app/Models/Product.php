<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;

/**
 * @method categories()
 * @method static where(string $string, bool $true)
 */
class Product extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;
    use Filterable;

    const BASE_PATH = 'app/public';
    const DIR_PRODUCTS = 'products';
    const PRODUCTS_PATH = self::BASE_PATH . '/' . self::DIR_PRODUCTS;

    protected $dates = ['deleted_at'];

    protected $fillable = ['product_name', 'slug', 'product_code', 'description', 'stock', 'price', 'featured', 'active', 'reviews', 'photo', 'category_id'];

    /**
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public static function createWithPhoto(array $data): Product
    {
        try {
            self::uploadPhoto($data['photo']);
            DB::beginTransaction();
            $data['photo'] = $data['photo']->hashName();
            $product = self::create($data);
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            self::deleteFile($data['photo']);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param UploadedFile $photo
     */
    private static function uploadPhoto(UploadedFile $photo)
    {
        $dir = self::photosDir();
        $photo->store($dir, ['disk' => 'public']);
    }

    /**
     * @param UploadedFile $photo
     */
    private static function deleteFile(UploadedFile $photo)
    {
        $path = self::photosPath();
        $photoPath = "{$path}/{$photo->hashName()}";
        if (file_exists($photoPath)) {
            \File::delete($photoPath);
        }
    }

    /**
     * @return string
     */
    public static function photosPath()
    {
        $path = self::PRODUCTS_PATH;
        return storage_path("{$path}");
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'product_name'
            ]
        ];
    }

    /**
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return asset("storage/{$this->photo_url_with_asset}");
    }

    /**
     * @return string
     */
    public function getPhotoUrlWithAssetAttribute()
    {
        $path = self::photosDir();
        return "{$path}/{$this->photo}";
    }

    /**
     * @return string
     */
    public static function photosDir()
    {
        return self::DIR_PRODUCTS;
    }

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    /**
     * @return BelongsToMany
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class);
    }

    /**
     * @return HasMany
     */
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    /**
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public function updateWithPhoto(array $data): Product
    {
        try {
            if (isset($data['photo'])) {
                self::uploadPhoto($data['photo']);
                $this->deletePhoto();
                $data['photo'] = $data['photo']->hashName();
            }
            DB::beginTransaction();
            $this->fill($data)->save();
            DB::commit();
            return $this;
        } catch (\Exception $e) {
            if (isset($data['photo'])) {
                self::deleteFile($data['photo']);
            }
            DB::rollBack();
            throw $e;
        }
    }

    private function deletePhoto()
    {
        $dir = self::photosDir();
        Storage::disk('public')->delete("{$dir}/{$this->photo}");
    }
}
