<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class SleepTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_sleep_entries()
    {
        $response = $this->call('GET', '/api/sleep');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('start', $content[0]);
        $this->assertArrayHasKey('finish', $content[0]);
        $this->assertArrayHasKey('startDate', $content[0]);
        $this->assertArrayHasKey('hours', $content[0]);
        $this->assertArrayHasKey('minutes', $content[0]);

        $this->assertEquals('9:00pm', $content[0]['start']);
        $this->assertEquals('8:00am', $content[0]['finish']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}
