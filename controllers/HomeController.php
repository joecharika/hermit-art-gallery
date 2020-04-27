<?php

namespace Controllers {

    use Hyper\Annotations\action;
    use Hyper\Controllers\Controller;
    use Hyper\Http\Request;
    use Models\Project;
    use function Hyper\Database\db;

    /**
     * Class HomeController
     * @package Controllers
     */
    class HomeController extends Controller
    {
        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function index(Request $request): string
        {
            return $this->view('home.index');
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function aboutUs(Request $request): string
        {
            return $this->view('home.about',
                null,
                null,
                [
                    'projects' => count(db(Project::class)->all()->toList())
                ]
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function contactUs(Request $request)
        {
            return $this->view('home.contact');
        }

    }
}
