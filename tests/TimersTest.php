<?php

use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class TimersTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_sleep_entries()
    {
        $response = $this->call('GET', '/api/timers');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('start', $content[0]);
        $this->assertArrayHasKey('finish', $content[0]);
        $this->assertArrayHasKey('startDate', $content[0]);
        $this->assertArrayHasKey('hours', $content[0]);
        $this->assertArrayHasKey('minutes', $content[0]);
        $this->assertArrayHasKey('activity', $content[0]);

        $this->assertArrayHasKey('id', $content[0]['activity']['data']);
        $this->assertArrayHasKey('name', $content[0]['activity']['data']);
        $this->assertArrayHasKey('color', $content[0]['activity']['data']);
        $this->assertArrayHasKey('totalDuration', $content[0]['activity']['data']);

//        $this->assertEquals('9:00pm', $content[0]['start']);
//        $this->assertEquals('8:00am', $content[0]['finish']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_sleep_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $sleep = [
            'start' => '2015-12-01 21:00:00',
            'finish' => '2015-12-02 08:30:00',
            'activity_id' => Activity::where('name', 'sleep')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $sleep);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
        $this->assertArrayHasKey('finish', $content);
        $this->assertArrayHasKey('startDate', $content);
        $this->assertArrayHasKey('hours', $content);
        $this->assertArrayHasKey('minutes', $content);

        $this->assertEquals('9:00pm', $content['start']);
        $this->assertEquals('8:30am', $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);
        $this->assertEquals(11, $content['hours']);
        $this->assertEquals(30, $content['minutes']);
        $this->assertEquals(690, $content['durationInMinutes']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_start_a_timer()
    {
        DB::beginTransaction();
        $this->logInUser();

        $timer = [
            'start' => '2015-12-01 21:00:00',
            'activity_id' => Activity::where('name', 'work')->first()->id
        ];

        $response = $this->call('POST', '/api/timers', $timer);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
//        $this->assertArrayHasKey('finish', $content);
        $this->assertArrayHasKey('startDate', $content);
//        $this->assertArrayHasKey('hours', $content);
//        $this->assertArrayHasKey('minutes', $content);

        $this->assertEquals('9:00pm', $content['start']);
//        $this->assertEquals('8:30am', $content['finish']);
        $this->assertEquals('01/12/15', $content['startDate']);
//        $this->assertEquals(11, $content['hours']);
//        $this->assertEquals(30, $content['minutes']);
//        $this->assertEquals(690, $content['durationInMinutes']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_timer()
    {
        DB::beginTransaction();
        $this->logInUser();

        $timer = Timer::forCurrentUser()->first();
        $finish = Carbon::today()->hour(23)->format('Y-m-d H:i:s');

        $response = $this->call('PUT', '/api/timers/'.$timer->id, [
            'finish' => $finish
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('start', $content);
        $this->assertArrayHasKey('finish', $content);
        $this->assertArrayHasKey('startDate', $content);
        $this->assertArrayHasKey('hours', $content);
        $this->assertArrayHasKey('minutes', $content);

        //Todo: test values are correct
        $this->assertEquals('11:00pm', $content['finish']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}
