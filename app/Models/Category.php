<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * @method static paginate(int $int)
 */
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['deleted_at'];

    protected $fillable = ['category_name', 'slug', 'description', 'active', 'photo'];

    const BASE_PATH = 'app/public';
    const DIR_CATEGORIES = 'categories';
    const CATEGORIES_PATH = self::BASE_PATH . '/' . self::DIR_CATEGORIES;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'category_name'
            ]
        ];
    }

    /**
     * @return string
     */
    public static function photosDir()
    {
        return self::DIR_CATEGORIES;
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
        $path = self::CATEGORIES_PATH;
        return storage_path("{$path}");
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
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public static function createWithPhoto(array $data): Category
    {
        try {
            self::uploadPhoto($data['photo']);
            DB::beginTransaction();
            $data['photo'] = $data['photo']->hashName();
            $category = self::create($data);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            self::deleteFile($data['photo']);
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithPhoto(array $data)
    {
        try {
            self::uploadPhoto($data['photo']);
            DB::beginTransaction();
            $this->deletePhoto($this->file_name);
            $this->save();
            DB::commit();
            return $this;
        } catch (\Exception $e) {
            self::deleteFile($data['photo']);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $file_name
     */
    private function deletePhoto($file_name)
    {
        $dir = self::photosDir($this->category_id);
        \Storage::disk('public')->delete("{$dir}/{$this->file_name}");
    }
}
