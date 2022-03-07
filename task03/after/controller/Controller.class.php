<?php

/**
 * Class Controller
 *
 * @author My Name <my.name@example.com>
 * @internal
 *
 */

namespace App\Controller;

use \App\Lib\Config as Config;
use \App\Model\Auth as Auth;
use \App\Model\Messages as Messages;
use \App\Model\User as User;

class Controller
{
    public $view = 'admin';
    public $title;
    public $lang;

    function __construct()
    {
        $this->title = Config::get('sitename');
        $this->lang = Config::get('lang');
    }

    protected function adminCheckRights()
    {
        $answer = '';

        $user_uuid = $_SESSION['user_id'];

        $role = strtolower(Auth::getGroupFromUUID($user_uuid));

        if ($role == 'administrator') {
            $answer = [
                'info' => Messages::MESSAGES_INFO[$this->lang]['registered'],
                'status' => 'ok',
                'role' => $role
            ];
        }

        if ($role == 'user') {
            $answer = [
                'info' => Messages::MESSAGES_INFO[$this->lang]['dontAccess'],
                'status' => 'error',
                'role' => $role
            ];
        }

        return $answer;
    }

    protected function adminCheckAuth($data)
    {
        User::sessionStart();

        if (!Auth::isAuthorized()) {
            Auth::login($data);
        }

        $answer = $this->adminCheckRights();

        return $answer;
    }

    protected function getRole($result)
    {
        $user_uuid = $_SESSION['user_id'];

        $role = strtolower(Auth::getGroupFromUUID($user_uuid));

        if ($result === '') {
            $answer = [
                'info' => Messages::MESSAGES_INFO[$this->lang]['registered'],
                'status' => 'ok',
                'role' => $role
            ];
        } else {
            $answer = [
                'info' => $result['info'],
                'status' => $result['status'],
                'role' => $role
            ];
        }
        return $answer;
    }

    protected function checkAuth($data)
    {
        User::sessionStart();
        if (Auth::isAuthorized()) {

            $answer = $this->getRole('');

        } else {

            $result = Auth::login($data);
            $answer = $this->getRole($result);

        }

        return $answer;
    }

    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function index($data)
    {
        return [];
    }
}
