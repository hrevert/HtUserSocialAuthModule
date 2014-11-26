<?php

return [
    'service_manager' => [
        'factories' => [
            'Hrevert\OauthClient\Manager\UserManager' => 'HtUserSocialAuthModule\Factory\UserManagerFactory',
        ],
    ],
];
