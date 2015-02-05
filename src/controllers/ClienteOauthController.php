<?php

namespace SIUToba\ClienteOauth\Controllers;

use App;
use Controller;
use Response;
use SIUToba\ClienteOauth\ClienteOauth;


class ClienteOauthController extends Controller
{


    public function getAccessToken()
    {
        /** @var $clienteOauth ClienteOauth */
        $clienteOauth = App::make('clienteOauth');


        $token = $clienteOauth->getToken();

        if (!empty($token)) {
            return Response::json(array('access_token' => $token->accessToken));
        } else {
            return Response::json(array('error' => 'No se pudo obtener el token'), 400);
        }
    }


}
