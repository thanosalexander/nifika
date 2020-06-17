<?php

use App\Http\Requests\AdminEntityRequest;
use App\Logic\App\EntityManager;

?>
<script type="text/javascript" src="<?= asset('vendor/jsvalidation/js/jsvalidation.js') ?>"></script>
<?php
switch ($entityName):
    default:
        if(EntityManager::hasEntityJqueryValidation($entityName)) {
            Config::set('jsvalidation.view', 'jsvalidation::admin.bootstrap');
            echo JsValidator::formRequest(AdminEntityRequest::class);
        }
        break;
endswitch;
?>