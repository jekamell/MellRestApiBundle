<?php

namespace Mell\Bundle\SimpleDtoBundle\Model;

/**
 * Class Dto
 * @package Mell\Bundle\SimpleDtoBundle\Model
 */
class Dto implements DtoInterface
{
    /** @var string */
    private $type;
    /** @var array */
    private $data;
    /** @var object|array */
    private $originData;
    /** @var string */
    private $group;

    /**
     * Dto constructor.
     * @param string $type
     * @param object $originalData
     * @param string|null $group
     * @param array $data
     */
    public function __construct($type, $originalData, $group = null, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
        $this->originData = $originalData;
        $this->group = $group;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func([$this, $name], $arguments);
        }
        if (strpos($name, 'get') === 0 || strpos($name, 'set') === 0) { // getter or setter called
            $property = lcfirst(substr($name, 3));
            if (strpos($name, 'get') === 0) { // getter called
                return array_key_exists($property, $this->data) ? $this->data[$property] : null;
            } else { // setter called
                $this->data[$property] = current($arguments);
            }
        }
    }

    /**
     * @return array
     */
    public static function getAvailableTypes()
    {
        return [
            DtoInterface::TYPE_INTEGER,
            DtoInterface::TYPE_FLOAT,
            DtoInterface::TYPE_STRING,
            DtoInterface::TYPE_BOOLEAN,
            DtoInterface::TYPE_ARRAY,
            DtoInterface::TYPE_DATE,
            DtoInterface::TYPE_DATE_TIME,
        ];
    }

    /**
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return $this->data;
    }

    /** @return array */
    public function getRawData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function setRawData(array $data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * Set dto source
     *
     * @param $data
     * @return $this
     */
    public function setOriginalData($data)
    {
        $this->originData = $data;

        return $this;
    }

    /**
     * get dto source
     *
     * @return mixed
     */
    public function getOriginalData()
    {
        return $this->originData;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function append($data)
    {
        if (is_array($data)) {
            $this->data = array_merge_recursive($this->data, $data);
        } else {
            $this->data[] = $data;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }
}
