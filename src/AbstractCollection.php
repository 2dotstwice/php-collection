<?php

namespace TwoDotsTwice\Collection;

use TwoDotsTwice\Collection\Exception\CollectionItemNotFoundException;
use TwoDotsTwice\Collection\Exception\CollectionKeyNotFoundException;

abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $items;

    public function __construct()
    {
        $this->items = array();
    }

    /**
     * @inheritdoc
     */
    public function with($item)
    {
        $this->guardObjectType($item);

        $copy = clone $this;
        $copy->items[] = $item;
        return $copy;
    }

    /**
     * @inheritdoc
     */
    public function withKey($key, $item)
    {
        $this->guardObjectType($item);

        $copy = clone $this;
        $copy->items[$key] = $item;
        return $copy;
    }

    /**
     * @inheritdoc
     */
    public function without($item)
    {
        $key = $this->getKeyFor($item);

        $copy = clone $this;
        unset($copy->items[$key]);
        return $copy;
    }

    /**
     * @inheritdoc
     */
    public function withoutKey($key)
    {
        if (!isset($this->items[$key])) {
            throw new CollectionKeyNotFoundException($key);
        }

        $copy = clone $this;
        unset($copy->items[$key]);
        return $copy;
    }

    /**
     * @inheritdoc
     */
    public function contains($item)
    {
        $this->guardObjectType($item);

        $filtered = array_filter(
            $this->items,
            function ($itemToCompare) use ($item) {
                return ($item == $itemToCompare);
            }
        );

        return !empty($filtered);
    }

    /**
     * @inheritdoc
     */
    public function getByKey($key)
    {
        if (!isset($this->items[$key])) {
            throw new CollectionKeyNotFoundException($key);
        }

        return $this->items[$key];
    }

    /**
     * @inheritdoc
     */
    public function getKeyFor($item)
    {
        $this->guardObjectType($item);

        $key = array_search($item, $this->items);

        if ($key === false) {
            throw new CollectionItemNotFoundException();
        }

        return $key;
    }

    /**
     * @inheritdoc
     */
    public function getKeys()
    {
        return array_keys($this->items);
    }

    /**
     * @inheritdoc
     */
    public function length()
    {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public static function fromArray(array $items)
    {
        $collection = new static();
        foreach ($items as $item) {
            $collection = $collection->with($item);
        }
        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return string
     */
    abstract protected function getValidObjectType();

    /**
     * @param mixed $object
     *   Object of which the type should be validated.
     *
     * @return bool
     *   TRUE if the object is of a valid type, FALSE otherwise.
     */
    protected function isValidObjectType($object)
    {
        $type = (string) $this->getValidObjectType();
        return ($object instanceof $type);
    }

    /**
     * @param mixed $object
     *   Object of which the type should be guarded.
     *
     * @throws \InvalidArgumentException
     *   When the provided object is not of the specified type.
     */
    protected function guardObjectType($object)
    {
        if (!$this->isValidObjectType($object)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expected instance of %s, found %s instead.',
                    $this->getValidObjectType(),
                    get_class($object)
                )
            );
        }
    }
}
