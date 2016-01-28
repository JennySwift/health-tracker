<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class RecipesStoreTest
 */
class RecipesStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_recipe()
    {
        $this->logInUser();

        $recipe = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/recipes', $recipe);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

//        $this->checkRecipeKeysExist($content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

}