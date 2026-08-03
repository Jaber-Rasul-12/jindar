<?php namespace Immovables\Immovables\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Contracts extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'  , \Backend\Behaviors\RelationController::class,     ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';  
    public $relationConfig = 'relation_config.yaml';

    public $requiredPermissions = [
        'contracts' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Immovables.Immovables', 'immovabless_settings', 'contracts');
         $this->addCss('/plugins/immovables/immovables/assets/css/style_button.css', 'immovables.immovables');

    }
}
