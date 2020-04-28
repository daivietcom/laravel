<?php namespace VnSource\Facades;

use Illuminate\Support\Facades\Facade;

class LangFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'translation'; }

}
