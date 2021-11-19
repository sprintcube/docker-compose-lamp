<?php
namespace Codeception\Specify;

/**
 * Helper for manipulating by an object property.
 *
 * @author Roman Ishchenko <roman@ishchenko.ck.ua>
 */
class ObjectProperty
{
    /**
     * @var mixed
     */
    private $_owner;

    /**
     * @var \ReflectionProperty|string
     */
    private $_property;

    /**
     * @var mixed
     */
    private $_initValue;

    /**
     * ObjectProperty constructor.
     *
     * @param $owner
     * @param $property
     * @param $value
     */
    public function __construct($owner, $property, $value = null)
    {
        $this->_owner = $owner;
        $this->_property = $property;

        if (!($this->_property instanceof \ReflectionProperty)) {
            $this->_property = new \ReflectionProperty($owner, $this->_property);
        }

        $this->_property->setAccessible(true);

        $this->_initValue = ($value === null ? $this->getValue() : $value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_property->getName();
    }

    /**
     * Restores initial value
     */
    public function restoreValue()
    {
        $this->setValue($this->_initValue);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_property->getValue($this->_owner);
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->_property->setValue($this->_owner, $value);
    }
}
