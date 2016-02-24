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

        //Check they are in the correct order
        $this->assertEquals('carrot', $content[0]['name']);
        $this->assertEquals('food', $content[0]['type']);

        $this->assertEquals('fruit salad', $content[1]['name']);
        $this->assertEquals('recipe', $content[1]['type']);

        $this->assertEquals('lettuce', $content[2]['name']);
        $this->assertEquals('food', $content[2]['type']);

        $this->assertEquals('watermelon', $content[3]['name']);
        $this->assertEquals('food', $content[3]['type']);
        $this->checkFoodKeysExist($content[3]);
        $this->checkFoodUnitKeysExist($content[3]['units']['data'][0]);

        //Check the units are correct for the watermelon
        $this->assertEquals('grams', $content[3]['units']['data'][0]['name']);

        foreach ($content as $foodOrRecipe) {
            $this->assertContains('t', $foodOrRecipe['name']);
        }

        //Check the default unit is the same as the unit in the units array,
        //so the select box works
        $this->assertEquals($content[3]['defaultUnit']['data'], $content[3]['units']['data'][0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

}