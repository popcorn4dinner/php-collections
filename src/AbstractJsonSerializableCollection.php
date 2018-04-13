<?php

namespace Popcorn4dinner\Collection;

abstract class AbstractJsonSerializableCollection extends AbstractPaginatedCollection implements \JsonSerializable
{


    /**
     * AbstractJsonSerializableCollection constructor.
     * @param array $items
     * @param int $offset
     * @param int $limit
     * @param int $totalAmount
     */
    public function __construct(AbstractCollection $items, int $offset, int $limit, int $totalAmount)
    {
        parent::__construct($items, $offset, $limit, $totalAmount);
    }

    public function jsonSerialize()
    {

        $items = $this->reduce(function ($item) {
            return static::serializeItem($item);
        });

        return [
            static::ITEMS             => $items,
            static::TOTAL_AMOUNT      => $this->getTotalAmount(),
            static::OFFSET            => $this->getOffset(),
            static::LIMIT             => $this->getLimit(),
            static::PAGE_SIZE         => $this->getPageSize(),
            static::CURRENT_PAGE      => $this->getCurrentPage(),
            static::HAS_NEXT_PAGE     => $this->hasNextPage(),
            static::HAS_PREVIOUS_PAGE => $this->hasPreviousPage(),
            static::AMOUNT_PAGES      => $this->getAmountPages()
        ];
    }




    abstract protected static function serializeItem($item): array;

    function __call($methodName, $arguments)
    {
        if ($this->isSaveToDelegate($methodName)) {
            return call_user_func_array([$this->original, $methodName], $arguments);
        } else {
            throw new DelegationException($this, $this->original, $methodName);
        }
    }

    private function isSaveToDelegate($methodName): bool
    {
        return method_exists($this->original, $methodName) && !in_array($methodName, static::FORBIDDEN_METHOD_NAMES);
    }
}
