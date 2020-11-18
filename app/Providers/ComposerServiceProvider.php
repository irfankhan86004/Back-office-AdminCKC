<?php
	
	namespace App\Providers;
	
	use App\Http\Composers\AdminSelectsPagePostComposer;
	use Illuminate\Support\ServiceProvider;
	
	class ComposerServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap the application services.
		 *
		 * @return void
		 */
		public function boot()
		{
			// Admin
			view()->composer('admin.redirects._form', AdminSelectsPagePostComposer::class);
		}
		
		/**
		 * Register the application services.
		 *
		 * @return void
		 */
		public function register()
		{
			//
		}
	}
