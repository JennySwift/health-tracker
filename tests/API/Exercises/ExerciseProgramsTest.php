<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExerciseProgramsTest
 */
class ExerciseProgramsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_programs()
    {
        $response = $this->call('GET', '/api/exercisePrograms');
        $content = json_decode($response->getContent(), true);
    //  dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_program()
    {
        DB::beginTransaction();
        $this->logInUser();

        $program = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', '/api/exercisePrograms', $program);
        $content = json_decode($response->getContent(), true);
     // dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

}