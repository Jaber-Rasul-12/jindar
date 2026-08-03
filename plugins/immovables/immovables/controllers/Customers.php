<?php namespace Immovables\Immovables\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Customers extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'customers' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Immovables.Immovables', 'immovabless_settings', 'customers');
    }
}
