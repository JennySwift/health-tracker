<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class CaloriesTest extends TestCase {

    use DatabaseTransactions;

    /**
 * @test
 */
    public function it_can_show_the_total_calories_for_today()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $response = $this->call('GET', '/api/calories/' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('forTheDay', $content);
        $this->assertArrayHasKey('averageFor7Days', $content);

        $this->assertEquals(50, $content['forTheDay']);
        $this->assertEquals(80, $content['averageFor7Days']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     */
    public function it_can_show_the_total_calories_for_yesterday()
    {
        $this->logInUser();

        $date = Carbon::yesterday()->format('Y-m-d');

        $response = $this->call('GET', '/api/calories/' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('forTheDay', $content);
        $this->assertArrayHasKey('averageFor7Days', $content);

        $this->assertEquals(60, $content['forTheDay']);
        $this->assertEquals(90, $content['averageFor7Days']);


        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }




}
