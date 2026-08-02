<?php namespace Immovables\Immovables\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Realestateds extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController' , \Backend\Behaviors\ImportExportController::class,    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $importExportConfig = 'import_export_config.yaml';

    public $requiredPermissions = [
        'realestateds' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Immovables.Immovables', 'immovabless_settings', 'realestateds');
    }
}
