<?php

namespace Popcorn4dinner\Collection\Examples;

use Popcorn4dinner\Collection\AbstractJsonSerializableCollection;

class ExampleJsonSerializableCollection extends AbstractJsonSerializableCollection
{
    protected function isCollectableInstance($item): bool
    {
        return is_a($item, ExampleItem::class);
    }

    protected static function serializeItem($item): array
    {
        return [ 'name' => $item->name];
    }
}
