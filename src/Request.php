<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Request;

use OpxCore\App\Interfaces\AppInterface;
use OpxCore\Request\Interfaces\RequestInterface;
use OpxCore\Request\Bags\ParameterBag;
use OpxCore\Request\Bags\ServerBag;

class Request implements RequestInterface
{
    /** @var AppInterface|null Application instance this request-hh captured by */
    protected ?AppInterface $app;

    protected $query;
    protected $request;
    protected $attributes;
    protected $cookies;
    protected $files;
    protected $server;
    protected $headers;
    protected $content;
    protected $languages;
    protected $charsets;
    protected $encodings;
    protected $acceptableContentTypes;
    protected $pathInfo;
    protected $requestUri;
    protected $baseUrl;
    protected $basePath;
    protected $method;
    protected $format;

    /**
     * Request constructor.
     *
     * @param AppInterface|null $app
     *
     * @return  void
     */
    public function __construct(?AppInterface $app = null)
    {
        $this->app = $app;
    }

    /**
     * Sets the parameters for this request-hh. If any parameter is null, globals will be used.
     * This method also re-initializes all properties.
     *
     * @param array|null $query The GET parameters
     * @param array|null $request The POST parameters
     * @param array|null $attributes The request-hh attributes (parameters parsed from the PATH_INFO, ...)
     * @param array|null $cookies The COOKIE parameters
     * @param array|null $files The FILES parameters
     * @param array|null $server The SERVER parameters
     * @param string|resource|null $content The raw body data
     *
     * @return  void
     */
    public function capture(?array $query = null, ?array $request = null, ?array $attributes = null, ?array $cookies = null, ?array $files = null, ?array $server = null, $content = null): void
    {
        $this->query = new ParameterBag($query ?? $_GET);
        $this->request = new ParameterBag($request ?? $_POST);
        $this->attributes = new ParameterBag($attributes ?? []);
        $this->cookies = new ParameterBag($cookies ?? $_COOKIE);
        $this->files = new ParameterBag($files ?? $_FILES);
        $this->server = new ServerBag($server ?? $_SERVER);
        $this->content = $content;

        $this->headers = new ParameterBag($this->server->getHeaders());
        $this->languages = null;
        $this->charsets = null;
        $this->encodings = null;
        $this->acceptableContentTypes = null;
        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }
}