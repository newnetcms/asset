<?php

namespace Newnet\Asset;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Class Asset
 *
 * @package Newnet\Asset\Facades
 *
 * @method \Newnet\Asset\Asset addVersioning()
 * @method \Newnet\Asset\Asset removeVersioning()
 * @method \Newnet\Asset\Asset prefix(?string $path = null)
 * @method \Newnet\Asset\Asset add(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method \Newnet\Asset\Asset style(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method \Newnet\Asset\Asset script(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method string styles()
 * @method string scripts()
 * @method string show()
 * @method string toHtml()
 */
class Factory
{
    use ForwardsCalls;

    /**
     * Asset Dispatcher instance.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * All of the instantiated asset containers.
     *
     * @var array
     */
    protected $containers = [];

    /**
     * Construct a new environment.
     *
     * @param  Dispatcher  $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get an asset container instance.
     * <code>
     *     // Get the default asset container
     *     $container = Asset::container();
     *     // Get a named asset container
     *     $container = Asset::container('footer');
     * </code>
     *
     * @param  string  $container
     * @return Asset
     */
    public function container(string $container = 'default'): Asset
    {
        if (!isset($this->containers[$container])) {
            $this->containers[$container] = new Asset($container, $this->dispatcher);
        }

        return $this->containers[$container];
    }

    /**
     * Magic Method for calling methods on the default container.
     * <code>
     *     // Call the "styles" method on the default container
     *     echo Asset::styles();
     *     // Call the "add" method on the default container
     *     Asset::add('jquery', 'js/jquery.js');
     * </code>
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->container(), $method, $parameters);
    }
}
