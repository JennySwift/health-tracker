<?php

use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class TimersTest extends TestCase {

    use DatabaseTransactions;

    /**
     * Todo: check values are correct? Check it doesn't error if there is no timer in progress?
     * @test
     * @return void
     */
    public function it_checks_for_timer_in_progress()
    {
        DB::beginTransaction();
        $this->logInUser();

        $timer = [
            'start' => '2015-12-01 21:00:00',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $timer);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $response = $this->call('GET', '/api/timers/checkForTimerInProgress');
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
        $this->assertArrayHasKey('startDate', $content);
        $this->assertArrayHasKey('activity', $content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }

}
