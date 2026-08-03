<?php namespace Immovables\Immovables\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateImmovablesImmovablesContracts extends Migration
{
    public function up()
    {
        Schema::create('immovables_immovables_contracts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('customer_owner_id')->unsigned();
            $table->integer('customer_tenant_id')->unsigned();
            $table->integer('realestated_id')->unsigned();
            $table->string('lease_purpose');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('total_duration');
            $table->double('rental_amount', 10, 0);
            $table->string('payment_method');
            $table->date('payment_day');
            $table->double('security_deposit', 10, 0);
            $table->double('default_days', 10, 0);
            $table->double('termination_notice_days', 10, 0);
            $table->double('renewal_notice_days', 10, 0);
            $table->string('judicial_district');
            $table->string('witness_one');
            $table->string('witness_tow');
            $table->string('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('customer_owner_id')
                        ->references('id')
                        ->on('immovables_immovables_customers')
                        ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_tenant_id')
                        ->references('id')
                        ->on('immovables_immovables_customers')
                        ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('realestated_id')
                        ->references('id')
                        ->on('immovables_immovables_realestateds')
                        ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('immovables_immovables_contracts');
    }
}
