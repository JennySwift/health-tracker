<?php

use App\Models\Timers\Activity;
use Carbon\Carbon;
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
        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', '/api/activities/getTotalMinutesForDay?date=' . $date);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('totalMinutes', $content[0]);
        $this->assertArrayHasKey('minutes', $content[0]);
        $this->assertArrayHasKey('hours', $content[0]);

        $this->assertEquals('sleep', $content[0]['name']);
        $this->assertEquals(735, $content[0]['totalMinutes']);
        $this->assertEquals(12, $content[0]['hours']);
        $this->assertEquals(15, $content[0]['minutes']);

        $this->assertEquals('work', $content[1]['name']);
        $this->assertEquals(60, $content[1]['totalMinutes']);
        $this->assertEquals(1, $content[1]['hours']);
        $this->assertEquals(0, $content[1]['minutes']);

        $this->assertEquals('untracked', $content[2]['name']);
        $this->assertEquals(645, $content[2]['totalMinutes']);
        $this->assertEquals(10, $content[2]['hours']);
        $this->assertEquals(45, $content[2]['minutes']);

        $this->assertEquals(200, $response->getStatusCode());
    }

}