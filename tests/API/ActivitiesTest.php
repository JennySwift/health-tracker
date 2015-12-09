<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ActivitiesTest
 */
class ActivitiesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_activities()
    {
        $response = $this->call('GET', '/api/activities');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('totalDuration', $content[0]);

        $this->assertEquals('sleep', $content[0]['name']);
        $this->assertEquals(3900, $content[0]['totalDuration']);
        $this->assertEquals(300, $content[1]['totalDuration']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}