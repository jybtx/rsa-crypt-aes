<?php

namespace Jybtx\RsaCryptAes\Faceds;

use Illuminate\Support\Facades\Facade;

class RsaCryptAesFaced extends Facade
{
	
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'RsaCryptAes';
    }
}