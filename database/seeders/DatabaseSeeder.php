<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalan di MySQL maupun SQLite, tanpa validasi ke layanan luar.
        // Buat/update admin berdasarkan email, bukan berdasarkan User::count().
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'           => 'Admin',
                'mobile'         => '081234567890', // wajib karena field unique
                'password'       => bcrypt(config('app.default_admin_password', 'password')),
                'utype'          => 'ADMIN',
                'remember_token' => Str::random(60),
            ]
        );
    }
    // public function run(): void
    // {

    //     $this->call([
    //         MonthSeeder::class
    //     ]);
    //     // User::factory(10)->create();

    //     // User::factory()->create([
    //     //     'name' => 'Test User',
    //     //     'email' => 'test@example.com',
    //     // ]);
    // }
}
