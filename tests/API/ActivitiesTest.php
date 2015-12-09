<?php

use App\Models\Timers\Activity;
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

    /**
     * @test
     * @return void
     */
    public function it_checks_the_timers_that_belong_to_the_activity_are_for_the_right_user()
    {
        $this->logInUser();
        $activities = Activity::forCurrentUser()->get();

        foreach ($activities as $activity) {
            foreach($activity->timers as $timer) {
                $this->assertEquals(1, $timer->user->id);
            }
        }
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_total_minutes_for_the_day_for_the_activity()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/activities/getTotalMinutesForDay');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('minutes', $content[0]);

        $this->assertEquals('sleep', $content[0]['name']);
        $this->assertEquals(555, $content[0]['minutes']);

        $this->assertEquals('work', $content[1]['name']);
        $this->assertEquals(60, $content[1]['minutes']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}