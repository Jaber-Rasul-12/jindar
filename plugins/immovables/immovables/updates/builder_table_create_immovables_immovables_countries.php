<?php namespace Immovables\Immovables\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateImmovablesImmovablesCountries extends Migration
{
    public function up()
    {
        Schema::create('immovables_immovables_countries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('immovables_immovables_countries');
    }
}
