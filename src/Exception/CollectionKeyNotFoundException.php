<?php

namespace TwoDotsTwice\Collection\Exception;

class CollectionKeyNotFoundException extends \Exception
{
    /**
     * @param string $key
     */
    public function __construct($key)
    {
        parent::__construct(sprintf('The specified key "%s" was not found in the collection.', $key), 404);
    }
}
