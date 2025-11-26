<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientId = '112583025699-4qiv5d413kebk4s53cc1450fopts7n3m.apps.googleusercontent.com'; // Replace with your actual Client ID
    public $clientSecret = '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'; // Replace with your actual Client Secret
    public $redirectUri = 'https://localhost/admission_ci4/auth/google_callback'; // Adjust if your base_url is different
}
