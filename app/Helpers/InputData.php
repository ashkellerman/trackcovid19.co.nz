<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

/**
 * Helper class for dealing with input data (e.g. data sent over HTTP,
 * API responses, deserialized data from storage etc). When accessing
 * items this class automatically checks that it exists and casts the
 * value to the expected type, otherwise returns a default value.
 */
class InputData implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * @var mixed[]|null|string|float|int
     */
    private $_data;

    /**
     * @param mixed[]|null|string|float|int $_data Input data
     */
    public function __construct($_data)
    {
        $this->_data = $_data;
    }

    /**
     * Cast to boolean.
     *
     * @param string|null $name The name/key of input item
     * @param bool|null $default The default value if the item doesn't exist
     *
     * @return bool|null
     */
    public function bool(?string $name = null, ?bool $default = false): ?bool
    {
        if (!$name) {
            if (is_array($this->_data)) {
                return $default;
            }

            return (bool) $this->_data;
        }

        [$data, $name] = $this->extractDataKey($name, $this->_data);

        $result = static::getValue($data, $name, $default);
        if (is_scalar($result)) {
            return (bool) $result;
        }

        return $default;
    }

    /**
     * Cast to an integer.
     *
     * @param string|null $name The name/key of input item
     * @param int|null $default The default value if the item doesn't exist
     *
     * @return int|null
     */
    public function int(?string $name = null, ?int $default = 0): ?int
    {
        if (!$name) {
            if (is_array($this->_data)) {
                return $default;
            }

            return (int) $this->_data;
        }

        [$data, $name] = $this->extractDataKey($name, $this->_data);

        $result = static::getValue($data, $name, $default);
        if (is_numeric($result)) {
            return (int) $result;
        }

        return $default;
    }

    /**
     * Cast to a float.
     *
     * @param string|null $name The name/key of input item
     * @param float|null $default The default value if the item doesn't exist
     *
     * @return float|null
     */
    public function decimal(?string $name = null, ?float $default = 0): ?float
    {
        if (!$name) {
            if (is_array($this->_data)) {
                return $default;
            }

            return (float) $this->_data;
        }

        [$data, $name] = $this->extractDataKey($name, $this->_data);

        $result = static::getValue($data, $name, $default);
        if (is_numeric($result)) {
            return (float) $result;
        }

        return $default;
    }

    /**
     * Cast to an string.
     *
     * @param string|null $name The name/key of input item
     * @param string|null $default The default value if the item doesn't exist
     *
     * @return null|string
     */
    public function string(?string $name = null, ?string $default = ''): ?string
    {
        if (!$name) {
            if (is_array($this->_data)) {
                return $default;
            }

            return (string) $this->_data;
        }

        [$data, $name] = $this->extractDataKey($name, $this->_data);

        $result = static::getValue($data, $name, $default);

        if ($this->raw($name) instanceof Collection) {
            return json_encode($this->raw($name));
        }

        if (is_scalar($result)) {
            return (string) $result;
        }

        return $default;
    }

    /**
     * Parse a DateTime.
     *
     * @param string|null $name The name/key of input item
     * @param string|null $timezone The timezone to use for the result, if null the default or input is used
     * @param string|null $default The default value if the item doesn't exist or is invalid
     *
     * @throws \Exception
     *
     * @return \DateTimeImmutable|null
     *
     */
    public function dateTime(?string $name = null, ?string $timezone = null, ?string $default = 'now'): ?\DateTimeImmutable
    {
        [$data, $name] = $this->extractDataKey($name, $this->_data);

        if ($default === null && !static::getValue($data, $name, $default)) {
            return null;
        }

        try {
            if ($timezone) {
                return new \DateTimeImmutable(
                    static::getValue($data, $name, $default) ?: $default,
                    new \DateTimeZone($timezone)
                );
            } else {
                return new \DateTimeImmutable(
                    static::getValue($data, $name, $default) ?: $default,
                    new \DateTimeZone('UTC')
                );
            }
        } catch (\Exception $exception) {
            if ($default === null) {
                return null;
            }
            if ($timezone) {
                return new \DateTimeImmutable($default, new \DateTimeZone($timezone));
            } else {
                return new \DateTimeImmutable($default, new \DateTimeZone('UTC'));
            }
        }
    }

    public function date(?string $name = null, ?string $timezone = null, ?string $default = 'now'): ?\DateTimeImmutable
    {
        return $this->dateTime($name, $timezone, $default);
    }

    /**
     * Returns a subarray of input data.
     *
     * @param string|null $name The name/key of input subarray
     * @param array $default The default value if the item doesn't exist or is not an array
     *
     * @return InputData
     */
    public function arr(?string $name = null, array $default = []): InputData
    {
        if (!$name) {
            if (!is_array($this->_data)) {
                return new static($default);
            }

            return new static($this->_data);
        }
        [$data, $name] = $this->extractDataKey($name, $this->_data);

        return new static(static::getValue($data, $name, $default));
    }

    /**
     * JSON decode a value from the input data.
     *
     * @param string|null $name The name/key of input item
     * @param mixed $default The default value if the item doesn't exist
     *
     * @return InputData
     */
    public function json(?string $name = null, $default = []): InputData
    {
        $string = $this->string($name);
        if (!$string) {
            return new static($default);
        }
        $value = json_decode($string);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $value = $default;
        }

        return new static($value);
    }

    /**
     * Returns a sub-object of input data.
     *
     * @param string|null $name The name/key of input item
     * @param mixed[] $default The default value if the item doesn't exist
     *
     * @return InputData
     */
    public function object($name, array $default = null): InputData
    {
        [$data, $name] = $this->extractDataKey($name, $this->_data);

        return static::getValue($data, $name, $default);
    }

    /**
     * Gets the raw value (unwraps the class) of data.
     *
     * @param string|null $name    The name/key of input item
     * @param mixed  $default The default value if the item doesn't exist
     *
     * @return mixed
     */
    public function raw($name, $default = null)
    {
        [$data, $name] = $this->extractDataKey($name, $this->_data);

        return static::getValue($data, $name, $default);
    }

    /**
     * Converts dot delimited notations to access sub items.
     *
     * @param string|null  $name The name/key of input item
     * @param mixed[]|null|string|float|int $data The default value if the item doesn't exist
     *
     * @return mixed[]|null|string|float|int
     */
    public static function extractDataKey(?string $name, $data)
    {
        $parts = explode('.', $name ?: '');
        while (count($parts) > 1) {
            $part = array_shift($parts);
            if (is_array($data)) {
                $data = isset($data[$part]) ? $data[$part] : [];
            } elseif (is_object($data)) {
                $data = isset($data->$part) ? $data->$part : [];
            } else {
                $data = [];
            }
            $name = $parts[0];
        }

        return [
            $data,
            $name,
        ];
    }

    /**
     * @param $data
     *
     * @return InputData
     */
    public function extract($data)
    {
        $newData = [];
        foreach ($data as $key => $type) {
            $newData[$key] = $this->$type($key);
        }

        return new static($newData);
    }

    /**
     * @param array $data
     *
     * @return InputData
     */
    public function extend(array $data)
    {
        $newData = [];
        foreach ($this->_data as $key => $type) {
            $newData[$key] = $this->_data[$key];
        }
        $newData = array_replace_recursive($newData, $data);

        return new static($newData);
    }

    /**
     * @return bool True if the input data is empty
     */
    public function isEmpty()
    {
        return empty($this->_data);
    }

    /**
     * @return bool True if the input data is an array
     */
    public function isArray(): bool
    {
        return is_array($this->_data);
    }

    /**
     * @return mixed[]|null|string|float|int
     */
    public function getData()
    {
        if (is_scalar($this->_data) || $this->_data === null) {
            return $this->_data;
        }
        array_walk_recursive($this->_data, function (&$value) {
            if ($value instanceof static) {
                $value = $value->_data;
            }
        });

        return $this->_data;
    }

    public function map($callback): InputData
    {
        $result = [];
        foreach ($this as $key => $value) {
            $result[$key->_data] = $callback($value);
        }

        return new static($result);
    }

    public static function getValue($data, $name, $default)
    {
        if (is_array($data)) {
            if (!array_key_exists($name, $data)) {
                return $default;
            }

            return $data[$name];
        }
        if (is_object($data)) {
            if (!isset($data->$name)) {
                return $default;
            }

            return $data->$name;
        }

        return $default;
    }

    public function exists($name)
    {
        [$data, $name] = $this->extractDataKey($name, $this->_data);

        if (is_array($data)) {
            return array_key_exists($name, $data);
        }
        if (is_object($data)) {
            return property_exists($data, $name);
        }

        return false;
    }

    public function get($name): InputData
    {
        if (is_array($this->_data)) {
            return isset($this->_data[$name]) ? new static($this->_data[$name]) : new static(null);
        }

        return isset($this->_data->$name) ?
            new static($this->_data->$name) :
            new static(null);
    }

    public function set($name, $value): InputData
    {
        if ($value instanceof static) {
            $value = $value->_data;
        }
        if (!isset($this->_data[$name]) || !$this->_data[$name]) {
            $this->_data[$name] = [];
        }
        if (is_array($this->_data)) {
            $this->_data[$name] = $value;
        } else {
            $this->_data->$name = $value;
        }

        return $this;
    }

    public function isset($name): bool
    {
        if (is_array($this->_data)) {
            return isset($this->_data[$name]);
        }

        return isset($this->_data->$name);
    }

    public function __toString()
    {
        return $this->string();
    }

    public function getIterator()
    {
        if (is_array($this->_data) || is_object($this->_data)) {
            foreach ($this->_data as $key => $value) {
                yield new static($key) => new static($value);
            }
        }
    }

    public function offsetExists($offset)
    {
        return $this->isset($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        return isset($this->$offset);
    }

    public function count()
    {
        return count($this->_data);
    }

    public function jsonSerialize()
    {
        return $this->getData();
    }

    public function toArray()
    {
        return $this->getData();
    }
}
