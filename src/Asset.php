<?php

namespace Newnet\Asset;

use Illuminate\Contracts\Support\Htmlable;

class Asset implements Htmlable
{
    /**
     * Asset Dispatcher instance.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * The asset container name.
     *
     * @var string
     */
    protected $name;

    /**
     * The asset container path prefix.
     *
     * @var string
     */
    protected $path = null;

    /**
     * All of the registered assets.
     *
     * @var array
     */
    protected $assets = [];

    /**
     * Create a new asset container instance.
     *
     * @param  string  $name
     * @param  Dispatcher  $dispatcher
     */
    public function __construct(string $name, Dispatcher $dispatcher)
    {
        $this->name = $name;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Enable asset versioning.
     *
     * @return $this
     */
    final public function addVersioning(): self
    {
        $this->dispatcher->addVersioning();

        return $this;
    }

    /**
     * Disable asset versioning.
     *
     * @return $this
     */
    final public function removeVersioning(): self
    {
        $this->dispatcher->removeVersioning();

        return $this;
    }

    /**
     * Set the asset container path prefix.
     *
     * @param  string|null  $path
     * @return $this
     */
    public function prefix(?string $path = null)
    {
        $this->path = $path;

        return $this;
    }

    public function add(
        $name,
        $source = null,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        if (!$source) {
            $source = $name;
        }

        if ($source) {
            $type = (strpos(pathinfo($source, PATHINFO_EXTENSION), 'css') === 0) ? 'style' : 'script';

            return $this->$type($name, $source, $dependencies, $attributes, $replaces);
        }

        return $this;
    }

    public function style(
        $name,
        $source = null,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        if (!$source) {
            $source = $name;
        }

        if (!array_key_exists('media', $attributes)) {
            $attributes['media'] = 'all';
        }

        $this->register('style', $name, $source, $dependencies, $attributes, $replaces);

        return $this;
    }

    public function script(
        $name,
        $source = null,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        if (!$source) {
            $source = $name;
        }

        $this->register('script', $name, $source, $dependencies, $attributes, $replaces);

        return $this;
    }

    protected function register(
        string $type,
               $name,
        string $source,
               $dependencies,
               $attributes,
               $replaces
    ): void {
        $dependencies = (array) $dependencies;
        $attributes = (array) $attributes;
        $replaces = (array) $replaces;

        if (is_array($name)) {
            $replaces = array_merge($name, $replaces);
            $name = '*';
        }

        $this->assets[$type][$name] = [
            'source'       => $source,
            'dependencies' => $dependencies,
            'attributes'   => $attributes,
            'replaces'     => $replaces,
        ];
    }

    /**
     * Get the links to all of the registered CSS assets.
     */
    public function styles(): string
    {
        return $this->group('style');
    }

    /**
     * Get the links to all of the registered JavaScript assets.
     */
    public function scripts(): string
    {
        return $this->group('script');
    }

    /**
     * Get the links to all the registered CSS and JavaScript assets.
     */
    public function show(): string
    {
        return $this->group('script').$this->group('style');
    }

    /**
     * Get content as a string of HTML.
     */
    public function toHtml(): string
    {
        return $this->show();
    }

    /**
     * Get all of the registered assets for a given type / group.
     *
     * @param  string  $group
     * @return string
     */
    protected function group(string $group): string
    {
        return $this->dispatcher->run($group, $this->assets, $this->path);
    }
}
