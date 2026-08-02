<?php 

namespace Immovables\Immovables\Updates;

use Immovables\Immovables\Updates\Seeders\CreateCountry;
use Seeder;
use Model;
class SeedersTable extends Seeder 

{

    public function run()

    {


        Model::unguard();
        $this->call(CreateCountry::class);



    }
}