<?php

namespace Popcorn4dinner\Collection;

abstract class AbstractPaginatedCollection extends AbstractCollection
{

    public const ITEMS = 'items';
    public const TOTAL_AMOUNT = '_total_amount';
    public const OFFSET = '_offset';
    public const LIMIT = '_limit';
    public const PAGE_SIZE = '_page_size';
    public const CURRENT_PAGE = '_current_page';
    public const HAS_NEXT_PAGE = '_has_next_page';
    public const HAS_PREVIOUS_PAGE = '_has_previous_page';
    public const AMOUNT_PAGES = '_amount_pages';


    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $totalAmount;


    public function __construct(array $items, int $offset, int $limit, int $totalAmount)
    {
        parent::__construct(...$items);

        $this->limit = $limit;
        $this->offset = $offset;
        $this->totalAmount = $totalAmount;
    }

    public static function fromResponseBody(array $responseBody, callable $populateItem)
    {
        $items = array_map(function ($i) use ($populateItem) {
            return $populateItem($i);
        }, $responseBody[static::ITEMS]);

        return new static($items, $responseBody[static::OFFSET], $responseBody[static::LIMIT], $responseBody[static::TOTAL_AMOUNT]);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->totalAmount - ($this->offset + $this->limit) > 0 ;
    }

    /**
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->offset > 0;
    }

    public function getAmountPages(): int
    {
        return (int)ceil($this->totalAmount / $this->getPageSize());
    }

    public function getCurrentPage(): int
    {
        return (int)floor($this->offset / $this->getPageSize()) + 1;
    }

    protected function createCloneOfThis()
    {
        return new static($this->items, $this->offset, $this->limit, $this->totalAmount);
    }
}
