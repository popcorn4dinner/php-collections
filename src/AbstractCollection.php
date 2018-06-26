<?php

namespace Popcorn4dinner\Collection;

abstract class AbstractCollection implements \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $items = [];

    public function __construct(...$items)
    {
        $this->setItems($items);
    }

    public function add($item)
    {
        if ($this->isCollectableInstance($item)) {
            $this->store($item);

            return $this;
        } else {
            $this->throwNotCollectableExceptionFor($item);
        }
    }

    public function setItems(array $items)
    {
        $this->items = [];

        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    public function count()
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function rewind()
    {
        return reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function valid()
    {
        return key($this->items) !== null;
    }

    public function each(callable $functionToApply): AbstractCollection
    {
        foreach ($this->items as $item) {
            $functionToApply($item);
        }

        return $this;
    }

    public function map(callable $functionToApply): AbstractCollection
    {
        $items = array_map($functionToApply, $this->items);

        return $this->createCloneOfThis()->setItems($items);
    }

    public function reject(callable $shouldBeRemoved): AbstractCollection
    {
        $shouldMatch = function ($item) use ($shouldBeRemoved) {
            return !$shouldBeRemoved($item);
        };

        return $this->filter($shouldMatch);
    }

    public function filter(callable $shouldMatch): AbstractCollection
    {
        $processedItems = [];
        foreach ($this->items as $item) {
            if ($shouldMatch($item)) {
                $processedItems[] = $item;
            }
        }

        return $this->createCloneOfThis()->setItems(array_values($processedItems));
    }

    public function unique(): AbstractCollection
    {
        $items = array_unique($this->items);

        return $this->createCloneOfThis()->setItems(array_values($items));
    }

    public function reduce(callable $convertItem): array
    {
        return array_map($convertItem, $this->items);
    }

    public function convertTo($collectionClass, $itemClass): AbstractCollection
    {
        $collection = new $collectionClass();

        if (is_a($collection, AbstractCollection::class)) {
            $convertedItems = array_map(function ($i) use ($itemClass) {
                return new $itemClass($i);
            }, $this->items);

            foreach ($convertedItems as $item) {
                $collection->add($item);
            }

            return $collection;
        } else {
            throw new \InvalidArgumentException("Given collections class is not compatible with AbstractColletion.");
        }
    }

    private function throwNotCollectableExceptionFor($item)
    {
        throw new \InvalidArgumentException("Invalid Item: ". __CLASS__ ." cannot collect items like ". get_class($item));
    }

    public function sort(callable $sort): AbstractCollection
    {
        usort($this->items, $sort);
        return $this;
    }

    public function split(callable $split): array
    {
        $splittedItems = [];

        foreach ($this->items as $item) {
            $key = $split($item);

            if (!isset($splittedItems[$key])) {
                $splittedItems[$key] = [];
            }

            $splittedItems[$key][]= $item;
        }

        return array_map(
            function ($items) {
                return $this->createCloneOfThis()->setItems($items);
            },
            $splittedItems
        );
    }

    public function toArray(): array
    {
        return array_values($this->items);
    }

    public function first()
    {
        return $this->items[0] ?? null;
    }

    public function last()
    {
        return array_values(array_slice($this->items, -1))[0] ?? null;
    }

    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    protected function createCloneOfThis()
    {
        return new static(...array_values($this->items));
    }

    abstract protected function isCollectableInstance($item): bool;

    protected function store($item)
    {
        $this->items[] = $item;
    }
}
