<?php namespace Immovables\Immovables\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateImmovablesImmovablesCustomers extends Migration
{
    public function up()
    {
        Schema::create('immovables_immovables_customers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('full_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('id_number');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('immovables_immovables_customers');
    }
}