<?php

namespace StepStone\SeedCommons\Tests;

use PHPUnit\Framework\TestCase;
use Popcorn4dinner\Collection\Examples\ExampleItem;
use Popcorn4dinner\Collection\Examples\ExamplePaginatedCollection;

class ExamplePaginatedCollectionTest extends ExampleCollectionTest
{
    private $items;

    function setUp()
    {
        parent::setUp();

        $this->items = [
            new ExampleItem('Fiona'),
            new ExampleItem('Hans'),
            new ExampleItem('Helga')
        ];
    }

    function test_fromResponse_returnsACorrectCollection()
    {
        $original = new ExamplePaginatedCollection($this->items, 0, 10, 61);
        $result = ExamplePaginatedCollection::fromResponseBody([
            ExamplePaginatedCollection::ITEMS => [
                ['name' => 'Fiona'],
                ['name' => 'Hans'],
                ['name' => 'Helga']
            ],
            ExamplePaginatedCollection::OFFSET => 0,
            ExamplePaginatedCollection::LIMIT => 10,
            ExamplePaginatedCollection::TOTAL_AMOUNT => 61
        ], function ($i) {
            return new ExampleItem($i['name']);
        });

        $this->assertEquals($original, $result);
        $this->assertEquals($original->getCurrentPage(), $result->getCurrentPage());
        $this->assertEquals($original->getLimit(), $result->getLimit());
        $this->assertEquals($original->getOffset(), $result->getOffset());
        $this->assertEquals($original->getTotalAmount(), $result->getTotalAmount());
    }

    function test_hasNextPage_returnsTrue_ifMorePagesExist()
    {
        $collection = new ExamplePaginatedCollection($this->items, 0, 10, 61);
        $this->assertTrue($collection->hasNextPage());

        $collection = new ExamplePaginatedCollection($this->items, 0, 10, 12);
        $this->assertTrue($collection->hasNextPage());
    }

    function test_hasNextPage_returnsTrue_ifNoMorePagesExist()
    {
        $collection = new ExamplePaginatedCollection($this->items, 55, 10, 61);
        $this->assertFalse($collection->hasNextPage());
    }

    function test_hasPreviousPage_returnsTrue_ifMorePagesExist()
    {
        $collection = new ExamplePaginatedCollection($this->items, 10, 10, 61);

        $this->assertTrue($collection->hasPreviousPage());
    }

    function test_hasPreviousPage_returnsTrue_ifNoMorePagesExist()
    {
        $collection = new ExamplePaginatedCollection($this->items, 0, 10, 61);

        $this->assertFalse($collection->hasPreviousPage());
    }

    function test_getCurrentPage_returnsTheCorrectPageNumber()
    {
        $pageOne = new ExamplePaginatedCollection($this->items, 0, 10, 61);
        $this->assertEquals(1, $pageOne->getCurrentPage());

        $pageTwo = new ExamplePaginatedCollection($this->items, 10, 10, 61);
        $this->assertEquals(2, $pageTwo->getCurrentPage());

        $pageThree = new ExamplePaginatedCollection($this->items, 40, 20, 61);
        $this->assertEquals(3, $pageThree->getCurrentPage());
    }

    function test_get_AmountPages_returnsTheRightAmountOfPages()
    {
        $result = new ExamplePaginatedCollection($this->items, 0, 10, 61);
        $this->assertEquals(7, $result->getAmountPages());

        $result = new ExamplePaginatedCollection($this->items, 0, 40, 61);
        $this->assertEquals(2, $result->getAmountPages());

        $result = new ExamplePaginatedCollection($this->items, 0, 5, 61);
        $this->assertEquals(13, $result->getAmountPages());
    }

    function test_get_AmountPages_returnsTheRightAmountOfPages_withLimitHigherThenTotalAmount()
    {
        $result = new ExamplePaginatedCollection($this->items, 0, 70, 61);
        $this->assertEquals(1, $result->getAmountPages());
    }

    function test_get_AmountPages_returnsTheRightAmountOfPages_withLimitEqualToTotalAmount()
    {
        $result = new ExamplePaginatedCollection($this->items, 0, 61, 61);
        $this->assertEquals(1, $result->getAmountPages());
    }

    function test_get_AmountPages_returnsTheRightAmountOfPages_withLimitOne()
    {
        $result = new ExamplePaginatedCollection($this->items, 0, 1, 61);
        $this->assertEquals(61, $result->getAmountPages());
    }
}
