<?php namespace Immovables\Immovables\Models;

use Model;
// use Winter\Storm\Database\Builder;
// use BackendAuth;
/**
 * Model
 */
use Jacob\Logbook\Traits\LogChanges;
class Realestated extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    
    use LogChanges;


    use \Winter\Storm\Database\Traits\Nullable;
    protected $nullable = ['type', 'detail' , 'area' , 'syria_price' , 'dollar_price' , 'purchase_date', 'point'];
    public $fillable = ['first_team', 'second_team', 'type', 'country_id', 'detail', 'area', 'syria_price', 'dollar_price', 'purchase_date', 'point'];


  public $logBookModelName = 'immovables.immovables::lang.plugin.countries';
  public static function changeLogBookDisplayColumn($column)
  {
    return 'immovables.immovables::lang.model.realestated.' . $column;
  }


    /**
     * @var string The database table used by the model.
     */
    public $table = 'immovables_immovables_realestateds';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'first_team' => 'required|string|max:255',
        'second_team' => 'required|string|max:255',
        'country_id' => 'required|integer|exists:immovables_immovables_countries,id',
        'type' => 'nullable|string|max:100',
        'detail' => 'nullable|string',
        'area' => 'nullable|numeric|min:0',
        'syria_price' => 'nullable|numeric|min:0',
        'dollar_price' => 'nullable|numeric|min:0',
        'purchase_date' => 'nullable|date',
        'point' => 'nullable|string',
    ];




    /**
     * Defines a "hasMany" relationship.
     *
     * - Establishes a one-to-many relationship between this model and the `nameClass` model.
     * - The foreign key `key_relation_id` is used to link multiple related records.
     * - This allows retrieving multiple `nameRelation` records associated with this model.
     *
     * @var array
     */
    public $belongsTo = [
        'country' => [Country::class, 'key' => 'country_id'],
    ];


        public $hasMany = [
        'contracts' => [Contract::class, 'key' => 'realestated_id'],

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
