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
     * @test
     * @return void
     */
    public function it_lists_the_entries_for_a_series()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/seriesEntries/1');
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('days_ago', $content[0]);
        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('description', $content[0]);
        $this->assertArrayHasKey('step_number', $content[0]);
//        $this->assertArrayHasKey('unit_id', $content[0]);
        $this->assertArrayHasKey('unit_name', $content[0]);
//        $this->assertArrayHasKey('default_unit_id', $content[0]);
        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);

        $this->assertEquals(Carbon::today()->format('d/m/y'), $content[0]['date']);
        $this->assertEquals(0, $content[0]['days_ago']);
        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals('1.00', $content[0]['step_number']);
//        $this->assertEquals(1, $content[0]['unit_id']);
        $this->assertEquals('reps', $content[0]['unit_name']);
//        $this->assertEquals(1, $content[0]['default_unit_id']);
        $this->assertEquals(2, $content[0]['sets']);
        $this->assertEquals(10, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(7, $content);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
