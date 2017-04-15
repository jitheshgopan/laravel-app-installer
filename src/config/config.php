<?php

return [
    "route" => '/install-it',
    "routeName" => 'AppInstaller',
    'afterInstallRedirectUrl' => function() {
        return route('home');
    }
];