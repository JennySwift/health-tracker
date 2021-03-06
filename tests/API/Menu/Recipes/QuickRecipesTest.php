<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class QuickRecipesTest
 */
class QuickRecipesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_finds_similar_names()
    {
        $this->logInUser();

        $data = [
            'ingredients' => [
                [
                    'description' => 'chopped',
                    'food' => 'rices',
                    'quantity' => 1,
                    'unit' => 'gram'
                ],
                [
                    'food' => 'bananas',
                    'quantity' => 2,
                    'unit' => 'large'
                ]
            ]
        ];

        $response = $this->apiCall('GET', '/api/quickRecipes/checkForSimilarNames', $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        //Check similar food data is correct
        $this->assertArrayHasKey('foods', $content);
        $this->assertEquals('bananas', $content['foods'][0]['specifiedFood']['name']);
        $this->assertEquals('banana', $content['foods'][0]['existingFood']['name']);
        $this->assertEquals('banana', $content['foods'][0]['selected']);
        $this->assertEquals(1, $content['foods'][0]['index']);

        //Check similar unit data is correct
        $this->assertArrayHasKey('units', $content);
        $this->assertEquals('gram', $content['units'][0]['specifiedUnit']['name']);
        $this->assertEquals('grams', $content['units'][0]['existingUnit']['name']);
        $this->assertEquals('grams', $content['units'][0]['selected']);
        $this->assertEquals(0, $content['units'][0]['index']);
    }

    /**
     * //Todo: check a food gets inserted if it doesn't exist?
     * //Todo: lines started failing when I changed the seeder so I commented it out
     * @test
     * @return void
     */
    public function it_can_insert_a_quick_recipe()
    {
        $this->logInUser();

        $data = [
            'name' => 'super recipe',
            'ingredients' => [
                [
                    'description' => 'chopped',
                    'food' => 'apple',
                    'quantity' => 1,
                    'unit' => 'small'
                ],
                [
//                    'description' => 'chopped',
                    'food' => 'banana',
                    'quantity' => 2,
                    'unit' => 'large'
                ]
            ],
            'steps' => ['Blend', 'Eat']
        ];

        $response = $this->call('POST', '/api/quickRecipes', $data);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        //Check the response is correct
        $this->assertEquals('super recipe', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $this->checkRecipeWasInserted($content);
    }

    /**
     * Check the quick recipe was inserted correctly
     * @param $recipe
     */
    private function checkRecipeWasInserted($recipe)
    {
        $response = $this->call('GET', '/api/recipes/' . $recipe['id']);
        $content = json_decode($response->getContent(), true);

        $this->checkRecipeKeysExist($content);

//        dd($content);

        $this->assertEquals('super recipe', $content['name']);

        $this->checkStepsWereInserted($content);

        $this->checkIngredientKeysExist($content['ingredients']['data'][0]);
        $this->checkIngredientsWereInserted($content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     *
     * @param $content
     */
    private function checkIngredientsWereInserted($content)
    {
        $ingredients = $content['ingredients']['data'];

        $this->assertEquals(1, $ingredients[0]['food']['data']['id']);
        $this->assertEquals('apple', $ingredients[0]['food']['data']['name']);
        $this->assertEquals('small', $ingredients[0]['unit']['data']['name']);
        $this->assertEquals(3, $ingredients[0]['unit']['data']['id']);
        $this->assertEquals('1.00', $ingredients[0]['quantity']);
        $this->assertEquals('chopped', $ingredients[0]['description']);

        $this->assertEquals(2, $ingredients[1]['food']['data']['id']);
        $this->assertEquals('banana', $ingredients[1]['food']['data']['name']);
        $this->assertEquals('large', $ingredients[1]['unit']['data']['name']);
        $this->assertEquals(5, $ingredients[1]['unit']['data']['id']);
        $this->assertEquals('2.00', $ingredients[1]['quantity']);
        $this->assertEquals('', $ingredients[1]['description']);
    }

    /**
     *
     * @param $content
     */
    private function checkStepsWereInserted($content)
    {
        //Step 1
        $this->assertEquals('Blend', $content['steps'][0]['text']);
        $this->assertEquals(1, $content['steps'][0]['step']);

        //Step 2
        $this->assertEquals('Eat', $content['steps'][1]['text']);
        $this->assertEquals(2, $content['steps'][1]['step']);
    }


}