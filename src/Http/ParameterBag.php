<?php declare(strict_types=1); // -*- coding: utf-8 -*-

namespace Adminimize\Http;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
// phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

/**
 * @package Inpsyde\GoogleTagManager\Http
 */
class ParameterBag implements Countable, IteratorAggregate
{

    /**
     * Parameter storage.
     *
     * @var array
     */
    protected $parameters;

    /**
     * ParameterBag constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Count elements of an object.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        return count($this->parameters);
    }

    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters.
     */
    public function add(array $parameters = [])
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters.
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys.
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key
     * @param mixed  $default The default value if the parameter key does not exist.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->parameters)
            ? $this->parameters[$key]
            : $default;
    }

    /**
     * Sets a parameter by name.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key
     *
     * @return bool true if the parameter exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Retrieve an external iterator,
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>,
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->parameters);
    }
}
