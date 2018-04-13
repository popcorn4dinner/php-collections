<?php

namespace StepStone\SeedCommons\Examples\Collection;

use StepStone\SeedCommons\Collection\AbstractCollection;

class CustomStorageCollection extends AbstractCollection
{
    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }

    protected function store($item)
    {
        $this->items[$item->name] = $item;
    }

}
