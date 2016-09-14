<?php

/*
 * This file is part of the Meta Reader package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MetaReader\Value;

/**
 * Value bag.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ValueBag implements \Countable
{
    /**
     * @var ValueInterface[]
     */
    private $values = [];

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            if (!$value instanceof ValueInterface) {
                $value = new MetaValue($value);
            }
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param string $defaultValue
     *
     * @return ValueInterface
     */
    public function get($key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        if ($defaultValue !== null) {
            return new MetaValue($defaultValue);
        }
    }

    /**
     * @param string         $key
     * @param ValueInterface $value
     *
     * @return $this
     */
    public function set($key, ValueInterface $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->values[$key]);
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->values[$key]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->values;
    }

    public function toArray()
    {
        $values = [];
        foreach ($this->all() as $key => $value) {
            $values[$key] = (string) $value;
        }

        return $values;
    }

    /**
     * @param ValueBag $attributes
     * @param bool     $override
     *
     * @return $this
     */
    public function merge(ValueBag $attributes, $override = false)
    {
        foreach ($attributes->all() as $key => $value) {
            if (!$this->has($key) || $override) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }
}
