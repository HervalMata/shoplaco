<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;

    const BASE_PATH = 'app/public';
    const DIR_USERS = 'users';
    const DIR_USER_PHOTO = self::DIR_USERS . '/photos';
    const USER_PHOTO_PATH = self::BASE_PATH . '/' . self::DIR_USER_PHOTO;

    protected $fillable = ['user_id', 'cidade_id', 'country', 'address', 'number', 'additional', 'province', 'cep', 'cpf', 'photo', 'telefone', 'mobile'];

    /**
     * @param $photo
     */
    public static function uploadPhoto($photo)
    {
        if (!$photo) {
            return;
        }
        $dir = self::photoDir();
        $photo->store($dir, ['disk' => 'public']);
    }

    /**
     * @return string
     */
    public static function photoDir()
    {
        $dir = self::DIR_USERS;
        return $dir;
    }

    /**
     * @param User $param
     * @param array $data
     */
    public static function saveProfile(User $user, array $data): UserProfile
    {
        if (array_key_exists('photo', $data)) {
            self::deletePhoto($user->profile);
            $data['photo'] = UserProfile::getPhotoHashName($data['photo']);
        }
        $user->profile->fill($data)->save();
        return $user->profile;
    }

    /**
     * @param UserProfile $profile
     */
    public static function deletePhoto(UserProfile $profile)
    {
        if (!$profile->photo) {
            return;
        }
        $dir = self::photoDir();
        Storage::disk('public')->delete("{$dir}/{$profile->photo}");
    }

    /**
     * @param UploadedFile|null $photo
     * @return string|null
     */
    private static function getPhotoHashName(UploadedFile $photo = null)
    {
        return $photo ? $photo->hashName() : null;
    }

    public static function deleteFile(UploadedFile $photo = null)
    {
        if (!$photo) {
            return;
        }
        $path = self::photosPath();
        $filePath = "{$path}/{$photo->hashName()}";
        if (file_exists($filePath)) {
            \File::delete($filePath);
        }
    }

    /**
     * @return string
     */
    public static function photosPath()
    {
        $path = self::USER_PHOTO_PATH;
        return storage_path($path);
    }

    /**
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset("storage/{$this->photo_url_base}") : $this->photo_url_base;
    }

    /**
     * @return string
     */
    public function getPhotoUrlBaseAttribute()
    {
        $path = self::photoDir();
        return $this->photo ? "{$path}/{$this->photo}" : 'https://secure.gravatar.com/avatar/8d0153955da67e7593b0cca28e3e4d75.jpg?s=150&r=g&d=mm';
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
}
