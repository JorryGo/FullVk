<?php

/**
 * Vk api SDK by Vladimir Letyagin (JorryGo)
 *
 * https://vk.com/jorrygo
 * https://github.com/JorryGo
 *
 * @package JorryGo\FullVk
 * @author JorryGo
 */

namespace JorryGo\FullVk;

use JorryGo\FullVk\essences\User;
use JorryGo\FullVk\traits\execute;

class Vk {
    use execute;

    public static $LOGS_DIRECTORY = __DIR__ . '/../logs/';

    public $app_id = null;
    public $app_secret= null;

    const AUTHORIZE_URL = 'https://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';

    public function __construct($app_id = null, $app_secret = null)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }

    public function getAuthLink(string $redirect_uri, string $scope = '', string $state = '', string $display = 'page') : string
    {
        $params = [
            'client_id' => $this->app_id,
            'redirect_uri' => $redirect_uri,
            'display' => $display,
            'scope' => $scope,
            'response_type' => 'code',
            'v' => $this->api_version,
            'state' => $state,
        ];

        return self::AUTHORIZE_URL . '?' . http_build_query($params);

    }

    public function getAuthToken(string $redirect_url, string $code, bool $return_user = false)
    {
        $params = [
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'redirect_uri' => $redirect_url,
            'code' => $code,
        ];

        $result = $this->execute('', $params, self::ACCESS_TOKEN_URL, false);

        if ($return_user) {
            return new User($result->access_token, $result->user_id);
        }

        return $result;
    }

    public function password_authorization(string $username, string $password) : User
    {

        $params = http_build_query([
            'grant_type' => 'password',
            'client_id' => $this->win_app_id,
            'client_secret' => $this->win_app_secret,
            'username' => $username,
            'password' => $password,

        ]);

        $auth_request_string = 'https://oauth.vk.com/token?' . $params;
        $response = $this->execute('', [],  $auth_request_string, false);

        $this->token = $response->token;

        return new User($response->access_token, $response->user_id);
    }

    public function getUser(string $access_token, int $user_id) : User
    {
        return new User($access_token, $user_id);
    }

    public static function setLogDirectory(string $dir) : bool
    {
        self::$LOGS_DIRECTORY = $dir;
        return true;
    }
}