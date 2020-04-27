<?php

namespace Controllers {

    use Hyper\Annotations\{action};
    use Hyper\Controllers\CRUDController;
    use Hyper\Functions\Debug;
    use Hyper\Http\Request;
    use Models\ProjectImage;

    /**
     * Class ProjectsController
     * @package Controllers
     */
    class ProjectsController extends CRUDController
    {
        public $redirects = [
            'create' => '/projects',
            'edit' => '/projects'
        ];

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function details(Request $request)
        {
            return $this->view('projects.read', $request->fromParam(null, [ProjectImage::class]));
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function index(Request $request)
        {
            $databaseContext = $this->db->search(@$request->query->search ?? '');

            return $this->view(
                'projects.index',
                $databaseContext->lists([ProjectImage::class])->toList(),
                null,
                [
                    'view' => @$request->query->view ?? 'normal'
                ]
            );
        }

        /**
         * @action
         * @param Request $request
         * @param null $model
         * @param null $message
         * @return string
         */
        public function create(Request $request, $model = null, $message = null)
        {
            return $this->view(
                'projects.write',
                $model,
                $message
            );
        }
    }
}