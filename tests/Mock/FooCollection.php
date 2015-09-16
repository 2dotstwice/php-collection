<?php

namespace TwoDotsTwice\Collection\Mock;

use TwoDotsTwice\Collection\AbstractCollection;

final class FooCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function getValidObjectType()
    {
        return Foo::class;
    }
}
