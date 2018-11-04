<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Adminimize\Http;

/**
 * @package Inpsyde\GoogleTagManager\Http
 */
class Request
{

    /**
     * Query string parameters ($_GET).
     *
     * @var ParameterBag
     */
    private $query;

    /**
     * Request body parameters ($_POST).
     *
     * @var ParameterBag
     */
    private $data;

    /**
     * Cookies ($_COOKIES)
     *
     * @var ParameterBag
     */
    private $cookies = [];

    /**
     * Server and execution environment parameters ($_SERVER).
     *
     * @var ParameterBag
     */
    private $server = [];

    /**
     * Request constructor.
     *
     * @param array $query
     * @param array $data
     * @param array $cookies
     * @param array $server
     */
    public function __construct(
        array $query = [],
        array $data = [],
        array $cookies = [],
        array $server = []
    ) {

        $this->query = new ParameterBag($query);
        $this->data = new ParameterBag($data);
        $this->cookies = new ParameterBag($cookies);
        $this->server = new ParameterBag($server);
    }

    /**
     * Creates a new instance from globals.
     *
     * @return static
     */
    public static function fromGlobals(): Request
    {
        // phpcs:disable
        // With the php's bug #66606, the php's built-in web server
        // stores the Content-Type and Content-Length header values in
        // HTTP_CONTENT_TYPE and HTTP_CONTENT_LENGTH fields.
        $server = $_SERVER;
        if ('cli-server' === PHP_SAPI) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }

        return new static($_GET, $_POST, $_COOKIE, $server);
        // phpcs:enable
    }

    /**
     * @return ParameterBag
     */
    public function cookies(): ParameterBag
    {
        return $this->cookies;
    }

    /**
     * @return ParameterBag
     */
    public function server(): ParameterBag
    {
        return $this->server;
    }

    /**
     * @return ParameterBag
     */
    public function query(): ParameterBag
    {
        return $this->query;
    }

    /**
     * @return ParameterBag
     */
    public function data(): ParameterBag
    {
        return $this->data;
    }
}
