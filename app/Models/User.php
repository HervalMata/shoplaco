<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @method static create(array $all)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    const ROLE_SELLER = 1;
    const ROLE_CUSTOMER = 2;

    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public static function createCustomer(array $data): User
    {
        try {
            if (isset($data['photo'])) {
                UserProfile::uploadPhoto($data['photo']);
            }
            DB::beginTransaction();
            $user = self::createCustomerUser($data);
            UserProfile::saveProfile($user, $data);
            DB::commit();
        } catch (\Exception $e) {
            if (isset($data['photo'])) {
                UserProfile::deleteFile($data['photo']);
            }
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return User
     */
    private static function createCustomerUser(array $data): User
    {
        $data['password'] = bcrypt(Str::random(16));
        $user = User::create($data);
        $user->role = user::ROLE_CUSTOMER;
        $user->save();
        return $user;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function fill(array $attributes)
    {
        !isset($attributes['password']) ?: $attributes['password'] = bcrypt($attributes['password']);
        return parent::fill($attributes);
    }

    /**
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateWithProfile(array $data): User
    {
        try {
            if (isset($data['photo'])) {
                UserProfile::uploadPhoto($data['photo']);
            }
            DB::beginTransaction();
            $this->fill($data);
            $this->save();
            UserProfile::saveProfile($this, $data);
            DB::commit();
        } catch (\Exception $e) {
            if (isset($data['photo'])) {
                UserProfile::deleteFile($data['photo']);
            }
            DB::rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->role,
            'profile' => [
                'has_photo' => $this->profile->photo ? true : false,
                'photo_url' => $this->profile->photo_url,
                'address' => $this->address,
                'additional' => $this->additional,
                'province' => $this->province,
                'telefone' => $this->telefone,
                'mobile' => $this->mobile,
                'cpf' => $this->cpf,
                /*'cidade' => $this->cidade->cidade,
                'estado' => $this->cidade->estado->estado*/
            ]
        ];
    }

    /**
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class)->withDefault();
    }
}
