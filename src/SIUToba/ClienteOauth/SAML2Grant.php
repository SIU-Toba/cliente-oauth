<?php


namespace SIUToba\ClienteOauth;


use League\OAuth2\Client\Grant\GrantInterface;
use League\OAuth2\Client\Token\AccessToken;

class SAML2Grant implements GrantInterface
{

    protected $assertion;

    public function setSamlAssertion($raw_assertion)
    {
        $this->assertion = $raw_assertion;
    }


    public function __toString()
    {
        return 'urn:ietf:params:oauth:grant-type:saml2-bearer';
    }

    public function handleResponse($response = [])
    {
        return new AccessToken($response);
    }

    public function prepRequestParams($defaultParams, $params)
    {
        $params['grant_type'] = 'urn:ietf:params:oauth:grant-type:saml2-bearer';


        if (!isset($params['assertion']) || empty($params['assertion'])) {
            if (!isset($this->assertion)) {

                throw new \BadMethodCallException('Missing assertion parameter with saml token');
            } else {
                $params['assertion'] = $this->assertion;
            }
        }

        return array_merge($defaultParams, $params);
    }
}