<?php

use App\Models\Menu\Recipe;
use App\Models\Tags\Tag;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

/**
 * Class RecipeSeeder
 */
class RecipeSeeder extends Seeder
{
    /**
     * @var
     */
    private $user;

    /**
     *
     */
    public function run()
    {
        Recipe::truncate();
        DB::table('taggables')->truncate();
        $users = User::all();
        $this->faker = Faker::create();

        foreach ($users as $user) {
            $this->user = $user;

            $this->createRecipe('delicious recipe');
            $this->createRecipe('fruit salad');
        }

    }

    /**
     *
     * @param $name
     */
    private function createRecipe($name)
    {
        $recipe = new Recipe([
            'name' => $name
        ]);
        $recipe->user()->associate($this->user);

        $tagIds = Tag::where('user_id', $this->user->id)
            ->where('for', 'recipe')
            ->lists('id')
            ->all();

        $tagIds = $this->faker->randomElements($tagIds, 2);

        $recipe->save();

        foreach ($tagIds as $id) {
            $recipe->tags()->attach([$id => ['taggable_type' => 'recipe']]);
        }
        $recipe->save();

    }

}