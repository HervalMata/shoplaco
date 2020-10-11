<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\User;
use App\Models\UserProfile;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create("pt_BR");
        \File::deleteDirectory(UserProfile::photosPath(), true);
        User::factory(1)->create([
            'email' => 'admin@user.com',
            'role' => User::ROLE_SELLER
        ])->each(function ($user) use ($faker) {
            Model::reguard();
            $user->updateWithProfile([
                'cidade_id' => Cidade::all()->random()->id,
                'address' => $faker->streetAddress,
                'number' => $faker->randomNumber(),
                'province' => $faker->region,
                'cep' => $faker->postcode,
                'cpf' => $faker->cpf,
                'mobile' => $faker->cellphone(true, true),
                'photo' => $this->getAdminPhoto(),
            ]);
            Model::unguard();
            $user->profile->save();
        });
        User::factory(1)->create([
            'email' => 'customer@user.com',
            'role' => User::ROLE_CUSTOMER
        ])->each(function ($user) use ($faker) {
            Model::reguard();
            $user->updateWithProfile([
                'cidade_id' => Cidade::all()->random()->id,
                'address' => $faker->streetAddress,
                'number' => $faker->randomNumber(),
                'province' => $faker->region,
                'cep' => $faker->postcode,
                'cpf' => $faker->cpf,
                'mobile' => $faker->cellphone(true, true),
                'photo' => $this->getCustomerPhoto(),
            ]);
            Model::unguard();
            $user->profile->save();
        });
        User::factory(50)->create([
            'role' => User::ROLE_CUSTOMER
        ]);
    }

    /**
     * @return UploadedFile
     */
    private function getAdminPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/users/1624_mod.png'),
            Str::random(16) . 'jpg'
        );
    }

    /**
     * @return UploadedFile
     */
    private function getCustomerPhoto()
    {
        return new UploadedFile(
            storage_path('app/faker/users/user_customer.png'),
            Str::random(16) . 'jpg'
        );
    }
}
