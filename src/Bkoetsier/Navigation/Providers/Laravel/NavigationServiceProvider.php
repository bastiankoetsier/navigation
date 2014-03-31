<?php namespace Bkoetsier\Navigation\Providers\Laravel;

use Bkoetsier\Navigation\Navigation;
use Bkoetsier\Navigation\Renderer\ListRenderer;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider {

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
		$this->package('bkoetsier/navigation');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('navigation.renderer',function($app){
			return new ListRenderer;
		});
		$this->app->bindShared('navigation',function($app){
			$nav = new Navigation();
			$nav->setRenderer($app->make('navigation.renderer'));
			return $nav;
		});
		$this->app->booting(function(){
			$loader = AliasLoader::getInstance();
			$loader->alias('Navigation','Bkoetsier\Navigation\Facades\Laravel\Navigation');
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
