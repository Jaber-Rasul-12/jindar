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

            // العلاقات (العملاء فقط)
            $table->integer('customer_owner_id')->unsigned();
            $table->integer('customer_tenant_id')->unsigned();

            // ===== ترويسة العقد =====
            $table->string('contract_day_name')->nullable();
            $table->date('contract_date')->nullable();

            // ===== المادة 1: بيانات العقار (حقول مباشرة) =====
            $table->string('property_governorate')->nullable();
            $table->string('property_city')->nullable();
            $table->string('property_district')->nullable();
            $table->string('property_street')->nullable();
            $table->string('property_building_no')->nullable();
            $table->string('property_floor')->nullable();
            $table->string('property_parcel_no')->nullable();
            $table->text('property_description')->nullable();
            $table->boolean('property_is_furnished')->default(false);
            $table->text('property_furniture_list')->nullable();

            // ===== المادة 2: الغرض من الإيجار =====
            $table->string('lease_purpose');

            // ===== المادة 3: مدة الإيجار =====
            $table->date('start_date');
            $table->date('end_date');
            $table->string('total_duration');
            $table->double('renewal_notice_days', 10, 0)->nullable();

            // ===== المادة 4: بدل الإيجار =====
            $table->double('rental_amount', 10, 0);
            $table->string('rent_period')->nullable();
            $table->string('payment_method');
            $table->date('payment_day');

            // ===== المادة 5: التأمين =====
            $table->double('security_deposit', 10, 0);

            // ===== المادة 9: الخدمات =====
            $table->string('utilities_on_tenant')->nullable();
            $table->string('utilities_on_lessor')->nullable();

            // ===== المادة 11 و 12 =====
            $table->double('default_days', 10, 0);
            $table->double('termination_notice_days', 10, 0);

            // ===== المادة 14 =====
            $table->string('judicial_district');

            // ===== المادة 15 =====
            $table->integer('copies_count')->nullable();
            $table->string('copy_holder')->nullable();

            // ===== الشهود =====
            $table->string('witness_one');
            $table->string('witness_tow');

            // ===== محضر استلام المأجور =====
            $table->date('handover_date')->nullable();
            $table->integer('keys_count')->nullable();
            $table->string('electric_meter_no')->nullable();
            $table->string('electric_reading')->nullable();
            $table->string('water_meter_no')->nullable();
            $table->string('water_reading')->nullable();
            $table->string('gas_meter')->nullable();
            $table->text('doors_windows_state')->nullable();
            $table->text('bathrooms_state')->nullable();
            $table->text('kitchen_state')->nullable();
            $table->text('heating_ac_state')->nullable();
            $table->text('other_notes')->nullable();

            // ===== الحالة والتواريخ =====
            $table->string('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // ===== المفاتيح الأجنبية (العملاء فقط) =====
            $table->foreign('customer_owner_id')
                        ->references('id')
                        ->on('immovables_immovables_customers')
                        ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_tenant_id')
                        ->references('id')
                        ->on('immovables_immovables_customers')
                        ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('immovables_immovables_contracts');
    }
}