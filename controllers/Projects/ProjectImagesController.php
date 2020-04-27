<?php

namespace Controllers {

    use Hyper\Annotations\{authorize};
    use Hyper\Controllers\CRUDController;

    /**
     * Class ProjectsController
     * @authorize
     * @package Controllers
     */
    class ProjectImagesController extends CRUDController
    {
        public $redirects = ['create' => '/projects'];
    }
}