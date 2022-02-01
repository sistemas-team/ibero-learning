<?php
namespace Database\Seeders;

use App\Helpers\Image;
use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "Laravel" => "F35144",
            "Nodejs" => "8DBC58",
            "Vuejs" => "41B881",
            "React" => "0CC1E9",
            "Deno" => "0098B6",
            "Amplify" => "FF9733",
        ];
        foreach($categories as $category => $bg) {
          Category::factory()->create([
                "name" => $category,
                "picture" => Image::image(storage_path('app/public/categories'), $category, $bg, 850, 350, false),
            ]);
        }
    }
}
