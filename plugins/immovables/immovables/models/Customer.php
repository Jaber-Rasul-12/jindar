<?php namespace Immovables\Immovables\Models;

use Model;

use Jacob\Logbook\Traits\LogChanges;

class Customer extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    use LogChanges;

    public $logBookModelName = 'immovables.immovables::lang.plugin.customers';
    public static function changeLogBookDisplayColumn($column)
    {
        return 'immovables.immovables::lang.model.customer.' . $column;
    }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'immovables_immovables_customers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'full_name' => 'required|string|max:255',
        'id_number' => 'required|string|max:255|unique:immovables_immovables_customers,id_number',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'contracts_owner' => [Contract::class, 'key' => 'customer_owner_id'],
        'contracts_tenant' => [Contract::class, 'key' => 'customer_tenant_id'],
    ];

    /**
     * Perform actions before deleting
     *
     * @throws \ValidationException
     */
    public function beforeDelete()
    {
        foreach ($this->hasMany as $relation => $details) {
            if ($this->{$relation}->count() > 0) {
                throw new \ValidationException(['name' => trans('immovables.immovables::lang.plugin.message_delete')]);
            }
        }
    }
}