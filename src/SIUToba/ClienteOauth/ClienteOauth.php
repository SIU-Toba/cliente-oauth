<?php
namespace SIUToba\ClienteOauth;


use League\OAuth2\Client\Grant\GrantInterface;
use League\OAuth2\Client\Provider\ProviderInterface;
use League\OAuth2\Client\Token\AccessToken;
use Siu\OauthCliente\modelo\SAML2Grant;

use Siu\OauthCliente\ProveedorSIU;

class ClienteOauth
{

    /**
     * @var ProviderInterface
     */
    protected $provider;

    protected $grant;

    function __construct(ProviderInterface $provider, $grantType)
    {
        $this->provider = $provider;
        $this->grant = $grantType;
    }


    /**
     * Retorna el access token. Sea por saml2, client credentials, y si es por
     * authorization code, ya tiene que existir el auth_code
     * @return mixed
     */
    function getToken()
    {
        $token = $this->provider->getAccessToken($this->grant);
        return $token;


    }

    /**
     * @param ProveedorSIU $provider
     * @return AccessToken
     */
    function getTokenFromAuthFlow(ProveedorSIU $provider)
    {
        //esto funciona, pero hay que arreglarlo!! No se usa ahora. Si se usa, arreglar antes de usar :)

        if (isset($_GET['error'])) {

            print_r($_GET['error'] . ": " . $_GET['error_description']);
            exit();
        }
        if (!isset($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->state;
            header('Location: ' . $authUrl);
            exit;

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
                'grant_type' => 'authorization_code'
            ]);
            return $token;

//            // Optional: Now you have a token you can look up a users profile data
//            try {
//
//                // We got an access token, let's now get the user's details
//                $userDetails = $provider->getUserDetails($token);
//
//                // Use these details to create a new profile
//                printf('Hello %s!', $userDetails->firstName);
//
//            } catch (Exception $e) {
//
//                // Failed to get user details
//                exit('Oh dear...');
//            }

        }
    }

}
