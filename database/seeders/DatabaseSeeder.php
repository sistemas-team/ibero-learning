<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Storage::deleteDirectory('categories');
        Storage::makeDirectory('categories');
        $this->call(CategorySeeder::class);

        $this->call(UserSeeder::class);

        Storage::deleteDirectory('courses');
        Storage::makeDirectory('courses');
        $this->call(CourseSeeder::class);

        Storage::deleteDirectory('units');
        Storage::makeDirectory('units');
    }
}
