<?php

namespace Dorcas\ModulesAppStore;
use Illuminate\Support\ServiceProvider;

class ModulesAppStoreServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadViewsFrom(__DIR__.'/resources/views', 'modules-app-store');
		$this->publishes([
			__DIR__.'/config/modules-app-store.php' => config_path('modules-app-store.php'),
		], 'config');
		/*$this->publishes([
			__DIR__.'/assets' => public_path('vendor/modules-app-store')
		], 'public');*/
	}

	public function register()
	{
		//add menu config
		$this->mergeConfigFrom(
	        __DIR__.'/config/navigation-menu.php', 'navigation-menu.modules-apps.sub-menu'
	     );
	}

}


?>