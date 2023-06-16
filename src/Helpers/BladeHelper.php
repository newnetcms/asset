<?php

namespace Newnet\Asset\Helpers;

use Newnet\Asset\Facades\Asset;

class BladeHelper
{
    static public function renderBlade(...$args)
    {
        list($name, $source, $dependencies, $attributes, $replaces) = array_pad($args, 5, []);

        Asset::add($name, $source, $dependencies, $attributes, $replaces);
    }
}
