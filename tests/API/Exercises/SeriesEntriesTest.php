<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class SeriesEntriesTest
 */
class SeriesEntriesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * For displaying the history of entries
     * of all exercises in a series
     * @test
     * @return void
     */
    public function it_lists_the_entries_for_a_series()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/seriesEntries/1');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]['exercise']['data']);
        $this->checkExerciseUnitKeysExist($content[0]['unit']);
        $this->checkExerciseEntryKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['exercise']['data']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['data']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['data']['stepNumber']);
        $this->assertEquals(1, $content[0]['exercise']['data']['defaultUnit']['data']['id']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(Carbon::today()->format('d/m/y'), $content[0]['date']);
        $this->assertEquals(0, $content[0]['daysAgo']);
        $this->assertEquals(2, $content[0]['sets']);
        $this->assertEquals(10, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
//        $this->assertCount(7, $content);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
