<?php

use App\Models\Weights\Weight;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class WeightsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_a_weight_entry()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $response = $this->call('GET', '/api/weights/' . $date);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('weight', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals($date, $content['date']);
        $this->assertEquals('50.0', $content['weight']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_weight_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $weight = [
            'weight' => 60,
            'date' => '2010-05-02'
        ];

        $response = $this->call('POST', '/api/weights', $weight);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkWeightKeysExist($content);

        $this->assertEquals(60, $content['weight']);
        $this->assertEquals('2010-05-02', $content['date']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_weight_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $weight = Weight::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/weights/'.$weight->id, [
            'weight' => 3.8
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkWeightKeysExist($content);

        $this->assertEquals(3.8, $content['weight']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_delete_a_weight_entry()
//    {
//        $this->logInUser();
//
//        $name = 'echidna';
//
//        $unit = new Unit([
//            'name' => 'echidna',
//            'for' => 'food'
//        ]);
//        $unit->user()->associate($this->user);
//        $unit->save();
//
//        $this->seeInDatabase('units', ['name' => 'echidna']);
//
//        $response = $this->call('DELETE', '/api/foodUnits/'.$unit->id);
//        $this->assertEquals(204, $response->getStatusCode());
//        $this->missingFromDatabase('units', ['name' => 'echidna']);
//
//        // @TODO Test the 404 for the other methods as well (show, update)
//        $response = $this->call('DELETE', '/api/units/0');
//        $this->assertEquals(404, $response->getStatusCode());
//    }
}
