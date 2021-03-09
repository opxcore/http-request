<?php

/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Request\Bags;

//use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use IteratorAggregate;
use ArrayIterator;
use Countable;
use function array_key_exists;
use function count;

/**
 * ParameterBag is a container for key/value pairs.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ParameterBag implements IteratorAggregate, Countable
{
    /** @var array Storage */
    protected array $parameters;

    /**
     * ParameterBag constructor.
     *
     * @param array $parameters
     *
     * @return  void
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the parameters.
     *
     * @return  array
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key
     * @param mixed $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    /**
     * Weather is parameter defined.
     *
     * @param string $key
     *
     * @return  bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

//    /**
//     * Replaces the current parameters by a new set.
//     */
//    public function replace(array $parameters = [])
//    {
//        $this->parameters = $parameters;
//    }

//    /**
//     * Adds parameters.
//     */
//    public function add(array $parameters = [])
//    {
//        $this->parameters = array_replace($this->parameters, $parameters);
//    }

//    /**
//     * Sets a parameter by name.
//     *
//     * @param mixed $value The value
//     */
//    public function set(string $key, $value)
//    {
//        $this->parameters[$key] = $value;
//    }

//    /**
//     * Removes a parameter.
//     */
//    public function remove(string $key)
//    {
//        unset($this->parameters[$key]);
//    }

//    /**
//     * Returns the alphabetic characters of the parameter value.
//     *
//     * @return string The filtered value
//     */
//    public function getAlpha(string $key, string $default = '')
//    {
//        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
//    }

//    /**
//     * Returns the alphabetic characters and digits of the parameter value.
//     *
//     * @return string The filtered value
//     */
//    public function getAlnum(string $key, string $default = '')
//    {
//        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
//    }

//    /**
//     * Returns the digits of the parameter value.
//     *
//     * @return string The filtered value
//     */
//    public function getDigits(string $key, string $default = '')
//    {
//        // we need to remove - and + because they're allowed in the filter
//        return str_replace(['-', '+'], '', $this->filter($key, $default, \FILTER_SANITIZE_NUMBER_INT));
//    }

//    /**
//     * Returns the parameter value converted to integer.
//     *
//     * @return int The filtered value
//     */
//    public function getInt(string $key, int $default = 0)
//    {
//        return (int)$this->get($key, $default);
//    }

//    /**
//     * Returns the parameter value converted to boolean.
//     *
//     * @return bool The filtered value
//     */
//    public function getBoolean(string $key, bool $default = false)
//    {
//        return $this->filter($key, $default, \FILTER_VALIDATE_BOOLEAN);
//    }

//    /**
//     * Filter key.
//     *
//     * @param mixed $default Default = null
//     * @param int $filter FILTER_* constant
//     * @param mixed $options Filter options
//     *
//     * @return mixed
//     * @see https://php.net/filter-var
//     *
//     */
//    public function filter(string $key, $default = null, int $filter = \FILTER_DEFAULT, $options = [])
//    {
//        $value = $this->get($key, $default);
//
//        // Always turn $options into an array - this allows filter_var option shortcuts.
//        if (!\is_array($options) && $options) {
//            $options = ['flags' => $options];
//        }
//
//        // Add a convenience check for arrays.
//        if (\is_array($value) && !isset($options['flags'])) {
//            $options['flags'] = \FILTER_REQUIRE_ARRAY;
//        }
//
//        if ((\FILTER_CALLBACK & $filter) && !(($options['options'] ?? null) instanceof \Closure)) {
//            trigger_deprecation('symfony/http-foundation', '5.2', 'Not passing a Closure together with FILTER_CALLBACK to "%s()" is deprecated. Wrap your filter in a closure instead.', __METHOD__);
//            // throw new \InvalidArgumentException(sprintf('A Closure must be passed to "%s()" when FILTER_CALLBACK is used, "%s" given.', __METHOD__, get_debug_type($options['options'] ?? null)));
//        }
//
//        return filter_var($value, $filter, $options);
//    }

    /**
     * Iterator for parameters.
     *
     * @return  ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->parameters);
    }

    /**
     * Count of parameters.
     *
     * @return  int
     */
    public function count(): int
    {
        return count($this->parameters);
    }
}
