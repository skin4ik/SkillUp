<?php

class Device implements ArrayAccess
{
    private $properties = array();

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    /**
     * @param mixed $offset
     * @return bool|mixed
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->properties[$offset] : false;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->properties[] = $value;
        } else {
            $this->properties[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }
}

$properties = array(
    'model' => 'htc mini',
    'color' => 'gray'
);

$device = new Device($properties, 'model');
echo "Characteristics :<br/>";
foreach ($properties as $key => $property) {
    var_dump($device[$key]);
}

$device['processor'] = 'Qualcomm Snapdragon 400';
print_r($device);
