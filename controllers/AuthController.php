<?php

namespace Controllers;

use Hyper\Annotations\action;
use Hyper\Annotations\authorize;
use Hyper\Application\Authorization;
use Hyper\Controllers\Controller;
use Hyper\Database\DatabaseContext;
use Hyper\Http\HttpMessage;
use Hyper\Http\HttpMessageType;
use Hyper\Http\Request;
use Models\User;
use function Hyper\Database\db;

/**
 * Class AuthController
 * @package Controllers
 */
class AuthController extends Controller
{
    /** @var Authorization $auth */
    protected $auth;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth = new Authorization;
        $this->db = new DatabaseContext('user');
    }

    /**
     * @action
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        return $request->redirectTo('sign-in', 'auth', null, null, $request->query);
    }

    /**
     * @action
     * @authorize
     * @param Request $request
     * @return string
     */
    public function signOut(Request $request)
    {
        return $request->redirectTo(
            'index',
            'home',
            null,
            $this->auth->logout()
                ? 'Logged out'
                : 'Failed to logout, please try again'
        );
    }

    /**
     * @action
     * @param Request $request
     * @return string
     */
    public function browserLogout(Request $request)
    {
        return $request->redirectToUrl(
            $request->previousUrl,
            db('claim')->delete($request->params->id)
                ? 'Logged out remote session.'
                : 'Failed to logout remote browser'
        );
    }

    /**
     * @action
     * @param Request $request
     * @return string
     */
    public function signUp(Request $request)
    {
        return $this->view('auth.register');
    }

    /**
     * @action
     * @param Request $request
     * @return string
     */
    public function postSignUp(Request $request)
    {
        $data = $request->data;
        $this->auth->register($data->username, $data->password);

        return $request->redirectTo(
            'index',
            'home',
            null,
            'Your registration was successful. Please verify your email or you will not be able to login next time.'
        );
    }

    /**
     * @action
     * @param Request $request
     * @return string
     */
    public function postSignIn(Request $request)
    {
        $data = $request->data;

        $result = User::isAuthenticated()
            ? $this->auth->getUser()
            : $this->auth->login($data->username, $data->password);

        if ($result instanceof \Hyper\Models\User) {
            return $request->redirect(@$request->query->return ?? '/', "Welcome {$this->auth->getUser()}");
        }

        return $this->signIn($request, $data, new HttpMessage($result, HttpMessageType::DANGER));
    }

    /**
     * @action
     * @param Request $request
     * @param null $model
     * @param null $message
     * @return string
     */
    public function signIn(Request $request, $model = null, $message = null)
    {
        if (User::isAuthenticated()) {
            $request->redirectTo('index', 'projects', null, $message);
        }

        return $this->view('auth.login', $model, $message);
    }

    /**
     * @action
     * @authorize
     * @param Request $request
     * @return string
     */
    public function password(Request $request)
    {
        return $this->profile($request);
    }

    /**
     * @action
     * @authorize
     * @param Request $request
     * @return string
     */
    public function profile(Request $request)
    {
        return $this->view(
            'auth.profile',
            $this->db->first('id', User::getId()),
            null,
            [
                'claims' => db('claim')
                    ->where('userId', User::getId())
                    ->toList()
            ]
        );
    }

    /**
     * @action
     * @authorize
     * @param Request $request
     * @return string
     */
    public function postPassword(Request $request)
    {
        $model = $this->db->first('id', User::getId());
        $oldPassword = $model->key;
        $newPassword = $model->key = $this->auth->encrypt($request->data->n_password, $model->salt);

        if ($oldPassword === $this->auth->encrypt($request->data->o_password, $model->salt)) {
            if ($request->data->n_password === $request->data->c_password) {
                if ($oldPassword !== $newPassword) {
                    if ($this->db->update($model)) {
                        return $request->redirectTo(
                            'password',
                            'auth',
                            null,
                            new HttpMessage('Successfully updated your password', HttpMessageType::SUCCESS),
                        );
                    }

                    return $request->redirectTo(
                        'password',
                        'auth',
                        null,
                        new HttpMessage('Failed to update your password', HttpMessageType::DANGER)
                    );
                } else {
                    return $request->redirectTo(
                        'password',
                        'auth',
                        null,
                        new HttpMessage('New password can\'t be the same as old password', HttpMessageType::WARNING)
                    );
                }
            } else {
                $request->redirectTo(
                    'password',
                    'auth',
                    null,
                    new HttpMessage('Failed to confirm password', HttpMessageType::DANGER)
                );
            }
        }

        return $request->redirectTo(
            'password',
            'auth',
            null,
            new HttpMessage('Password is incorrect', HttpMessageType::DANGER)
        );
    }

    /**
     * @action
     * @authorize
     * @param Request $request
     * @return string
     */
    public function postProfile(Request $request)
    {
        return $request->redirectTo(
            'profile',
            'auth',
            null,
            $this->db->update($request->bind($this->db->first('id', User::getId())))
                ? 'Successfully updated your profile'
                : 'Failed to update your profile'
        );
    }
}
