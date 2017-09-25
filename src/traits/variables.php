<?php

namespace JorryGo\FullVk\traits;

/**
 * Trait with variables for use in Vk class
 * @package JorryGo\FullVk\traits
 * @author JorryGo
 */
trait variables {

    /**
     * variable with version of vk api
     * @var string
     */
    public $api_version = '5.68';


    /**
     * variable with url for requests to vk api
     * @var string
     */
    private $request_url = 'https://api.vk.com/method/';


    /**
     * Official windows application id for authorization by login and password
     * @var string
     */
    private $win_app_id = '3697615';


    /**
     * Official windows application secret for authorization by login and password
     * @var string
     */
    private $win_app_secret = 'AlVXZFMUqyrnABp8ncuU';

    /**
     * Vk username for authorization
     * @var string
     */
    private $username;

    /**
     * Vk password for authorization
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    public $token;

}