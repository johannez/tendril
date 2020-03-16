<?php

/**
* Base class for defining API calls.
**/

namespace Hertz\Api;

abstract class RestApi
{
    protected $namespace;
    protected $base_url;


    public function __construct()
    {
        $this->namespace = 'api';
        $this->base_url = get_site_url() . '/';
    }

    /**
    * Register all the routes for this resource.
    *
    * Example:
    * register_rest_route($this->namespace, '/blog', [
    *  'methods' => 'GET',
    *   'callback' => [$this, 'index']
    * ]);
    *
    * @see https://developer.wordpress.org/reference/functions/register_rest_route 
    */
    abstract public function registerRoutes();
}
