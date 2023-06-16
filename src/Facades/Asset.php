<?php

namespace Newnet\Asset\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Asset
 *
 * @package Newnet\Asset\Facades
 *
 * @method static \Newnet\Asset\Asset addVersioning()
 * @method static \Newnet\Asset\Asset removeVersioning()
 * @method static \Newnet\Asset\Asset prefix(?string $path = null)
 * @method static \Newnet\Asset\Asset add(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method static \Newnet\Asset\Asset style(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method static \Newnet\Asset\Asset script(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method static string styles()
 * @method static string scripts()
 * @method static string show()
 * @method static string toHtml()
 *
 * @see \Newnet\Asset\Factory
 */
class Asset extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'newnet.asset';
    }
}
