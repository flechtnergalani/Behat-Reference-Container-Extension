<?php

namespace Atamis\Extension\ReferenceContainer\Container;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Atamis\Extension\ReferenceContainer\Exception\ReferenceNotFoundException;

/**
 * Container storing all references and their associated items in memory.
 */
class ArrayContainer
{
    /**
     * @var array Array storing all references in a two-dimensional way
     */
    protected $container;
    protected $accessor;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->container = [];
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }
    
    /**
     * {@inheritdoc}
     */
    public function setReference($name, $item)
    {
        $this->clearReference($name);
        $this->addReference($name, $item);
    }
    
    /**
     * {@inheritdoc}
     */
    public function clearReference($name)
    {
        if ($this->hasReference($name)) {
            $this->container[$name] = null;
        }
    }
    
    /**
     * Check if the internal container knows about the reference.
     *
     * @param string $name
     * @return bool
     */
    public function hasReference($name)
    {
        return isset($this->container[$name]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function addReference($name, $item)
    {
        if (!$this->hasReference($name)) {
            $this->container[$name] = [];
        }
        $this->container[$name][] = $item;
    }
    
    /**
     * {@inheritdoc}
     * @throws \Atamis\Exception\ReferenceNotFoundException
     */
    public function getReference($name, $position = -1)
    {
        if ($position < 0) {
            if (!$this->hasReference($name)) {
                throw new ReferenceNotFoundException($name, $position);
            }
            
            $position = $this->getCountFor($name) + $position;
        }
        
        if (!$this->hasReferenceAt($name, $position)) {
            throw new ReferenceNotFoundException($name, $position);
        }
        
        return $this->container[$name][$position];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCountFor($name)
    {
        return count($this->container[$name]);
    }
    
    /**
     * Check if the internal container knows about the reference and has an
     * item associated with it at the given position.
     *
     * @param string $name
     * @param int $position
     * @return bool
     */
    public function hasReferenceAt($name, $position)
    {
        if ($position < 0)
            $position = $this->getCountFor($name) + $position;
        return ($this->hasReference($name)
            && isset($this->container[$name][$position]));
    }
    
    public function getReferenceByAccessor($name)
    {
        if (!$this->accessor->getValue($this->container, $name)) {
            throw new ReferenceNotFoundException($name);
        }
        return $this->accessor->getValue($this->container, $name);
    }

    public function getReferenceWhere($name, callable $condition)
    {
        if (!$this->hasReference($name)) {
            throw new ReferenceNotFoundException($name);
        }
        
        return array_filter($this->container[$name], $condition);
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->container = [];
    }
    
    public function showAllData()
    {
        var_dump($this->container);
    }
}
