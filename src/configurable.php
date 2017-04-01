<?php

namespace matchdav\Fp;

class Configurable implements \Iterator
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $keyIndices = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param $data
     */
    public function __construct($data = null)
    {
        if (!Types::isPrimitive($data)) {
            $this->data = $data;
        }
        $this->updateKeyIndices();
    }

    public function current()
    {
        return Access::get($this->data, $this->key());
    }

    /**
     * @param $keypath
     */
    public function get($keypath)
    {
        return Access::get($this->data, $keypath);
    }

    /**
     * @param $keypath
     */
    public function has($keypath)
    {
        $result = $this->get($keypath);
        if (is_null($result)) {
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->keyIndices[$this->position];
    }

    /**
     * @return array
     */
    public function keys()
    {
        return Collections::keys($this->data);
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @param $keys
     * @return mixed
     */
    public function props($keys)
    {
        if (empty($keys)) {
            return $this->data;
        }
        if (is_string($keys)) {
            $keys = explode(' ', $keys);
        }
        $result = [];
        foreach ($keys as $key) {
            $val = $this->get($key);
            if (!empty($val)) {
                $result[$key] = $val;
            }
        }
        if (is_object($this->data)) {
            return (object) $result;
        }
        return $result;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param string $keypath
     * @param mixed $value
     * @return self
     */
    public function set($keypath, $value)
    {
        Access::set($this->data, $keypath, $value);
        $this->updateKeyIndices();
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (is_array($this->data)) {
            return array_slice($this->data, 0);
        }
        return get_object_vars($this->data);
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    public function valid()
    {
        return isset($this->keyIndices[$this->position]);
    }

    /**
     * @return array
     */
    public function values()
    {
        return Collections::values($this->data);
    }

    private function updateKeyIndices()
    {
        $this->keyIndices = Collections::keys($this->data);
    }
}
