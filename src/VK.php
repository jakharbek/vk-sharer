<?php

namespace jakharbek\vksharer;

class VK
{
    /**
     * @var $client_id
     * для груп с отрицательным значением
     */
    public $client_id;
    /**
     * @var $app_id идентификатор приложение
     */
    public $app_id;
    /**
     * @var $secret_code Защищённый ключ
     */
    public $secret_code;
    /**
     * @var $vk Vkontakte
     */
    public $vk;
    /**
     * @var string $redirect_uri
     */
    public $redirect_uri = "https://oauth.vk.com/blank.html";
    /**
     * @var $login_url самоопределаюищий
     */
    public $login_url;
    public $response_type = "token";
    /**
     * @var array $premissions scopes
     */
    public $premissions = [
        'notify',
        'friends',
        'photos',
        'audio',
        'video',
        'stories',
        'pages',
        'status',
        'notes',
        'messages',
        'wall',
        'ads',
        'offline',
        'docs',
        'groups',
        'notifications',
        'stats',
        'email',
        'market'
    ];

    /**
     * init
     */
    public function init(){
        $vk = new Vkontakte([]);
        $vk->setSecret($this->secret_code);
        $vk->setAppId($this->app_id);
        $vk->setRedirectUri($this->redirect_uri);
        $vk->setResponceType($this->response_type);
        $vk->setScope($this->premissions);
        $this->login_url = $vk->getLoginUrl();
        $this->connection();
    }

    /**
     * @return string $token;
     */
    public function getToken(){
        $file = "vk.token";
        if(!file_exists($file)){
            $this->setToken("");
        }
        return file_get_contents($file);
    }

    /**
     * @param $token
     * @return bool|int
     */
    public function setToken($token){
        $file = "vk.token";
        return file_put_contents($file,$token);
    }

    /**
     * connection in api
     */
    public function connection(){
        $token = $this->getToken();
        $this->vk = new Vkontakte(['access_token' => $token]);
    }

    /**
     * @param $message
     * @param $link
     * @return mixed
     */
    public function post($message,$link = ""){
        return $this->vk->api('wall.post',[
            'owner_id' => $this->client_id,
            'message' => $message,
            'attachments' => $link
        ]);
    }

    /**
     * @param $client_id
     * @param $app_id
     * @param $secret_code
     * @return VK
     */
    public static function &getInstance($client_id, $app_id, $secret_code)
    {
        $vk = new self();
        $vk->client_id = $client_id;
        $vk->app_id = $app_id;
        $vk->secret_code = $secret_code;
        $vk->init();
        return $vk;
    }
}