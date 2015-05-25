<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class MasterSeeder extends Seeder {
  
  protected $faker;
  
  public function __construct()
  {
      $this->faker = Faker::create();
  }

  
}