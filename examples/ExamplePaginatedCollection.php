<?php

namespace StepStone\SeedCommons\Examples\Collection;


use StepStone\SeedCommons\Collection\AbstractPaginatedCollection;

class ExamplePaginatedCollection extends AbstractPaginatedCollection
{

    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }

}
