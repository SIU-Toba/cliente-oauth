<?php


    return array(
        'oauth2' => array(
            'grant_type' => 'saml2',
            'clientId'  =>  'arai_autogestion',
            'clientSecret'  =>  '123456',
            'redirectUri'   =>  'http://localhost:8000/usuarios/backend/access_token',
            'scopes' => array('cuenta_bancaria'),
            'urlAuthorize' => 'http://localhost:8000/oauth/authorize',
            'urlAccesToken' => 'http://localhost/oauth/token' //este va por canal seguro (sin :8000)
        )
    );