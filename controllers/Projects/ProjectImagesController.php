<?php

namespace Controllers {

    use Hyper\Annotations\{action, authorize};
    use Hyper\Controllers\CRUDController;
    use Hyper\Http\HttpMessage;
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
            $project = db(Project::class)->first('id', $request->params->id, SqlOperator::equal, null,
                [ProjectImage::class]);
            $img = db(ProjectImage::class)->first('id', $request->params->param1);

            return $this->view('projects.read',
                $project,
                new HttpMessage('You are editing a project image now!', null, '#edit-topic'),
                [
                    'image' => $img
                ]
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function postEdit(Request $request)
        {
            $model = $this->db->first('id', $request->data->id);

            $this->redirects['edit'] = "/projects/details/$model->projectId";

            return parent::postEdit($request);
        }


        /**
         * @action
         * @param Request $request
         * @param null|ProjectImage $model
         * @param null $message
         * @return string
         */
        public function delete(Request $request, $model = null, $message = null)
        {
            $model = $model ?? $request->fromParam();

            return $this->view(
                'projects.read',
                db(Project::class)->first('id', $model->projectId, SqlOperator::equal, null, [ProjectImage::class]),
                $message ?? new HttpMessage("Are you sure you want to delete artwork, $model->title", 'warning',
                    "/projectImages/delete-confirmed/$model->id")
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function deleteConfirmed(Request $request)
        {
            /** @var ProjectImage $model */
            $model = $model ?? $request->fromParam();

            $projectUrl = "/projects/details/$model->projectId";

            if ($this->db->delete($model)) {
                return $request->redirect($projectUrl, 'Artwork deleted');
            }

            return $this->delete(
                $request,
                $model,
                $message ?? new HttpMessage('Failed to delete! Try again?', 'warning',
                    "/projectImages/delete-confirmed/$model->id")
            );
        }
    }
}