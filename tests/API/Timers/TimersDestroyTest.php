<?php

use App\Models\Timers\Timer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TimersDestroyTest
 */
class TimersDestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_timer()
    {
        DB::beginTransaction();
        $this->logInUser();

        $timer = Timer::forCurrentUser()->first();

        $response = $this->call('DELETE', '/api/timers/'.$timer->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/timer/' . $timer->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}