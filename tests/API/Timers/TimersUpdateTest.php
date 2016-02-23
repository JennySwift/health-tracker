<?php

use App\Models\Timers\Timer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersUpdateTest
 */
class TimersUpdateTest extends TestCase
{
    use DatabaseTransactions;

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

        $this->checkTimerKeysExist($content);

        //Todo: test values are correct
        $this->assertEquals($finish, $content['finish']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }
}