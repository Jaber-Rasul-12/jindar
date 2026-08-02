<?php namespace Immovables\Immovables\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Countries extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'country' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Immovables.Immovables', 'immovabless_settings', 'country');
    }

   public function formGetRedirectUrl($context = null, $model = null)
    {
        $url = post('url');


        if (($url == 'create') && !empty($url)) {
            return "immovables/immovables/countries/create";
        } else {
            if ((post("close") == 1) && !empty(post("close"))) {
                return "immovables/immovables/countries";
            } else {
                return "immovables/immovables/countries/update/$model->id";
            }
        }
    }
}
