<?php

namespace Popcorn4dinner\Collection\Examples;

use Popcorn4dinner\Collection\AbstractCollection;

class ExampleCollection extends AbstractCollection
{
    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }
}
