<?php namespace Bkoetsier\Navigation\Facades\Laravel;

use Illuminate\Support\Facades\Facade;

class Navigation extends Facade{

	protected static function getFacadeAccessor()
	{
		return 'bkoetsier.nav';
	}
} 