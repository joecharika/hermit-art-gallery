<?php

namespace Controllers {

    use Hyper\Annotations\{action, authorize};
    use Hyper\Controllers\CRUDController;
    use Hyper\Functions\Debug;
    use Hyper\Http\Request;
    use Hyper\SQL\SqlOperator;
    use Models\Project;
    use Models\ProjectImage;
    use function Hyper\Database\db;

    /**
     * Class ProjectsController
     * @authorize
     * @package Controllers
     */
    class ProjectImagesController extends CRUDController
    {
        public $redirects = [
            'create' => '/projects',
            'edit' => '/projects'
        ];

        /**
         * @action
         * @param Request $request
         * @param null $model
         * @param null $message
         * @return string
         */
        public function edit(Request $request, $model = null, $message = null)
        {
            $project = db(Project::class)->first('id', $request->params->id, SqlOperator::equal, null, [ProjectImage::class]);
            $img = db(ProjectImage::class)->first('id', $request->params->param1);

            return $this->view('projects.read',
                $project,
                'You are editing a project image now!',
                [
                    'image' => $img
                ]
            );
        }
    }
}