<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersIndexTest
 */
class TimersIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_sleep_entries()
    {
        $response = $this->call('GET', '/api/timers?byDate=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('shortDate', $content[0]);
        $this->assertArrayHasKey('orderIndex', $content[0]);
        $this->assertArrayHasKey('day', $content[0]);

        $this->assertArrayHasKey('start', $content[0][4]);
        $this->assertArrayHasKey('finish', $content[0][4]);
        $this->assertArrayHasKey('startPosition', $content[0][4]);
        $this->assertArrayHasKey('finishPosition', $content[0][4]);
        $this->assertArrayHasKey('startHeight', $content[0][4]);
        $this->assertArrayHasKey('color', $content[0][4]);

//        $this->assertArrayHasKey('id', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('name', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('color', $content[0]['activity']['data']);
//        $this->assertArrayHasKey('totalDuration', $content[0]['activity']['data']);

//        $this->assertEquals('9:00pm', $content[0]['start']);
//        $this->assertEquals('8:00am', $content[0]['finish']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_timers_for_a_day()
    {
        $date = Carbon::yesterday();
        $response = $this->call('GET', '/api/timers?date=' . $date->copy()->format('Y-m-d'));
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkTimerKeysExist($content[0]);

        $this->assertEquals(75, $content[0]['durationInMinutesForDay']);
        $this->assertEquals(180, $content[1]['durationInMinutesForDay']);
        $this->assertEquals(60, $content[2]['durationInMinutesForDay']);
        $this->assertEquals(510, $content[3]['durationInMinutesForDay']);
        $this->assertEquals(75, $content[4]['durationInMinutesForDay']);
        $this->assertEquals(180, $content[5]['durationInMinutesForDay']);
        $this->assertEquals(60, $content[6]['durationInMinutesForDay']);
        $this->assertEquals(510, $content[7]['durationInMinutesForDay']);

        //Todo: check the values are correct
        $this->assertContains($date->copy()->format('Y-m-d'), $content[0]['start']);
//        $this->assertEquals($date->copy()->format('Y-m-d'), $content[0]['startDate']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}