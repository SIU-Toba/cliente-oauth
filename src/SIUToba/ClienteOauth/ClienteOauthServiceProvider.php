<?php

namespace SIUToba\ClienteOauth;

use Auth;
use Config;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;

class ClienteOauthServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/siu_oauth_settings.php' => config_path('siu_oauth_settings.php'),
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['clienteOauth'] = $this->app->share(function ($app) {

            $config = config('siu_oauth_settings.oauth2');
            if (!$config) {
                throw new Exception("No se encontró la configuracion de Oauth");
            }

            $proveedor = new ProveedorSIU($config);

            $grant = $app->make($config['grant_type'] . '-Grant');

            $cliente = new ClienteOauth($proveedor, $grant);

            return $cliente;
        });


        $this->app['saml2-Grant'] = $this->app->share(function ($app) {

            $user = Auth::user();

            if (!isset($user->assertion)) {
                throw new \Exception ("Error, el usuario no tiene una aserción de saml. Agregarle una el usuario de Laravel");
            } else {
                $assertion = $user->assertion;
            }

            $grant = new SAML2Grant();
            $grant->setSamlAssertion($assertion);
            return $grant;
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
