<?php use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class MenuAutocompleteTest
 */
class MenuAutocompleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Both foods and recipes should be in the results
     * @test
     * @return void
     */
    public function it_can_autocomplete_the_menu()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/menu?typing=t');
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        //They should be in this order (fruit salad first, watermelon second)
        //Check fruit salad (recipe) is first
        $this->assertEquals('fruit salad', $content[0]['name']);
        $this->assertEquals('recipe', $content[0]['type']);

        //Check watermelon (food) is second
        $this->assertEquals('watermelon', $content[1]['name']);
        $this->assertEquals('food', $content[1]['type']);
        $this->checkFoodKeysExist($content[1]);
        $this->checkFoodUnitKeysExist($content[1]['units']['data'][0]);

        //Check the units are correct for the watermelon
        $this->assertEquals('grams', $content[1]['units']['data'][0]['name']);

        foreach ($content as $foodOrRecipe) {
            $this->assertContains('t', $foodOrRecipe['name']);
        }

        //Check the default unit is the same as the unit in the units array,
        //so the select box works
        $this->assertEquals($content[1]['defaultUnit']['data'], $content[1]['units']['data'][0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

}