<?php

namespace Popcorn4dinner\Collection\Examples;

use Popcorn4dinner\Collection\AbstractPaginatedCollection;

class ExamplePaginatedCollection extends AbstractPaginatedCollection
{

    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }
}
