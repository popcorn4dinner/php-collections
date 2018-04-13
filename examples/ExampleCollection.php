<?php

namespace Popcorn4dinner\Collection\Examples;

use StepStone\SeedCommons\Collection\AbstractCollection;

class ExampleCollection extends AbstractCollection
{
    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }
}
