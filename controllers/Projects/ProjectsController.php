<?php

namespace Controllers {

    use Hyper\Annotations\action;
    use Hyper\Controllers\CRUDController;
    use Hyper\Http\HttpMessage;
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

        /**
         * @action
         * @param Request $request
         * @param null $model
         * @param null $message
         * @return string
         */
        public function edit(Request $request, $model = null, $message = null)
        {
            return $this->view(
                'projects.write',
                $model ?? $request->fromParam(),
                $message
            );
        }

        /**
         * @action
         * @param Request $request
         * @param null $model
         * @param null $message
         * @return string
         */
        public function delete(Request $request, $model = null, $message = null)
        {
            $model = $model ?? $request->fromParam();
            return $this->view(
                'projects.index',
                $this->db->all()->lists([ProjectImage::class])->toList(),
                $message ?? new HttpMessage("Are you sure you want to delete project, $model->title", 'warning', "/projects/delete-confirmed/$model->id")
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function deleteConfirmed(Request $request)
        {
            $model = $model ?? $request->fromParam();

            if ($this->db->delete($model))
                return $request->redirect('/projects', 'Project deleted');

            return $this->delete(
                $request,
                $model,
                $message ?? new HttpMessage('Failed to delete! Try again?', 'warning', "/projects/delete-confirmed/$model->id")
            );
        }
    }
}