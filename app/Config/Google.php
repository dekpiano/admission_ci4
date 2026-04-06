<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientId = '29638025169-aeobhq04v0lvimcjd27osmhlpua380gl.apps.googleusercontent.com';
    public $clientSecret = 'RSANANTRl84lnYm54Hi0icGa';
    public $redirectUri;

    public function __construct()
    {
        parent::__construct();
        // Use site_url() to dynamically create the redirect URI based on the app's baseURL
        $this->redirectUri = site_url('auth/google_callback');
    }
}
