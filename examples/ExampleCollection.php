<?php

namespace StepStone\SeedCommons\Examples\Collection;

use StepStone\SeedCommons\Collection\AbstractCollection;

class ExampleCollection extends AbstractCollection
{
    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }

}
