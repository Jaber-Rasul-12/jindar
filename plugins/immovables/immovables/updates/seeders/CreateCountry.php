<?php 

namespace Immovables\Immovables\Updates\Seeders;

use Seeder;
use DB ;

class CreateCountry extends Seeder
{
    public function run()
    {
        DB::table('immovables_immovables_countries')->insert([
            ['name' => 'الحسكة'],
            ['name' => 'قامشلو'],
            ['name' => 'دمشق'],
            ['name' => 'تل براك '],
            ['name' => 'معبدة'],
            ['name' => 'تربسبية'],
            ['name' => 'كوباني'],
            ['name' => 'مالكية'],
            ['name' => 'درباسية'],
            ['name' => 'جل اغا'],
            ['name' => 'الرقة'],
            ['name' => 'قامشلو خلف حديقة'],
            ['name'=>'ديريك'],
        ]);
    }
}   
