<?php namespace Immovables\Immovables\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateImmovablesImmovablesRealestateds extends Migration
{
    public function up()
    {
        Schema::create('immovables_immovables_realestateds', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('first_team');
            $table->string('second_team');
            $table->string('type')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->text('detail')->nullable();
            $table->double('area', 10, 0)->nullable();
            $table->double('syria_price', 10, 0)->nullable();
            $table->double('dollar_price', 10, 0)->nullable();
            $table->date('purchase_date')->nullable();
            $table->text('point')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('country_id')
                        ->references('id')
                        ->on('immovables_immovables_countries')
                        ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('immovables_immovables_realestateds');
    }
}
