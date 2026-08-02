<?php namespace Immovables\Immovables\Models;

use Model;
// use Winter\Storm\Database\Builder;
// use BackendAuth;
/**
 * Model
 */


use Jacob\Logbook\Traits\LogChanges;
class Country extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use LogChanges;

  public $logBookModelName = 'immovables.immovables::lang.plugin.countries';
  public static function changeLogBookDisplayColumn($column)
  {
    return 'immovables.immovables::lang.model.country.' . $column;
  }
    



    /**
     * @var string The database table used by the model.
     */
    public $table = 'immovables_immovables_countries';

    /**
     * @var array Validation rules
     */
    public $rules = [
              'name' => 'required|string|max:255|unique:immovables_immovables_countries,name',

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
    public $hasMany = [
        
        'immovables' => [
            'Immovables\Immovables\Models\Immovable',
            'key' => 'country_id',
            'otherKey' => 'id',
        ],
    
    ];

    
    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = [];


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
