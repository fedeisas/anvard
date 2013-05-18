<?php namespace Atticmedia\Anvard;

use Hybrid_Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AnvardServiceProvider extends ServiceProvider {

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
		$this->package('atticmedia/anvard');
        require_once(__DIR__.'/routes.php');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
        $this->registerHybridAuth();
        $this->registerAnvard();
    }

    private function registerHybridAuth()
    {
        $this->app['hybridauth'] = $this->app->share(function($app) {
            $config = $app['config'];
            $haconfig = $config['anvard::hybridauth'];
            $haconfig['base_url'] = $app['url']->route('anvard.routes.endpoint');
            $instance = new Hybrid_Auth($haconfig);
            return $instance;
        });
    }

    private function registerAnvard() {
        $this->app['anvard'] = $this->app->share(function($app) {
            $config = array(
                'db' => $app['config']['anvard::db'],
                'hybridauth' => $app['config']['anvard::hybridauth'],
                'models' => $app['config']['anvard::models'],
                'routes' => $app['config']['anvard::routes'],
            );
            $instance = new Anvard($config);
            $instance->setLogger($app['log']);
            return $instance;
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('anvard', 'hybridauth');
	}

}