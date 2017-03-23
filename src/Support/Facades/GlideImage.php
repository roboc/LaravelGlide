<?php

namespace Roboc\Glide\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class GlideImage
 * @package Roboc\Glide\Support\Facades
 */
class GlideImage extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'glide-image';
    }
}
