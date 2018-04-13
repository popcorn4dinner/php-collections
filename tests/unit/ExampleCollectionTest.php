<?php

namespace Popcorn4dinner\Collection\Tests;

use PHPUnit\Framework\TestCase;
use Popcorn4dinner\Collection\Examples\ExampleCollection;
use Popcorn4dinner\Collection\Examples\ExampleItem;

class ExampleCollectionTest extends TestCase
{
    private $collection;
    private $items = [];

    function setUp()
    {
        parent::setUp();

        $this->items[]= new ExampleItem('Fiona');
        $this->items[]= new ExampleItem('Hans');
        $this->items[]= new ExampleItem('Helga');


        $this->collection = new ExampleCollection();
        $this->collection->add($this->items[0]);
        $this->collection->add($this->items[1]);
    }


    /********
     * ADD *
     ********/

    function test_add_adds_returns_itself()
    {
        $this->assertSame(
            $this->collection->add($this->items[0]),
            $this->collection
        );
    }

    function test_add_adds_one_item()
    {
        $this->collection->add($this->items[2]);
        $this->assertEquals(3, count($this->collection));
    }

    /********
     * SET ITEMS *
     ********/

    function test_setItems_adds_returns_itself()
    {
        $this->assertSame(
            $this->collection->setItems([$this->items[0]]),
            $this->collection
        );
    }

    function test_setItems_adds_one_item()
    {
        $this->collection->setItems([$this->items[2]]);
        $this->assertEquals(1, count($this->collection));
    }


    /**********
     * REJECT *
     **********/

    function test_that_reject_reduces_the_offers_in_collection()
    {

        $result = $this->collection->reject(function ($item) {
            return $item->name === 'Hans';
        });

        $this->assertEquals(1, count($result));
    }

    function test_that_reject_does_not_reduce_the_offers_in_collection()
    {
        $result = $this->collection->reject(function () {
            return false;
        });

        $before = count($this->collection);
        $after = count($result);
        $this->assertTrue($before === $after);
    }

    function test_reject_adds_returns_itself()
    {
        $this->assertInstanceOf(
            ExampleCollection::class,
            $this->collection->reject(function () {
                return true;
            })
        );
    }

    /**********
     * FILTER *
     **********/

    function test_that_filter_reduces_the_offers_in_collection()
    {
        $result = $this->collection->filter(function ($item) {
            return $item->name === 'Hans';
        });

        $this->assertEquals(1, count($result));
    }

    function test_that_filter_does_not_reduce_the_offers_in_collection()
    {
        $result = $this->collection->filter(function () {
            return false;
        });

        $before = count($this->collection);
        $after = count($result);
        $this->assertTrue($before > $after);
    }

    function test_filter_adds_returns_itself()
    {
        $this->assertInstanceOf(
            ExampleCollection::class,
            $this->collection->filter(function () {
                return true;
            })
        );
    }

    /*********
     * COUNT *
     *********/

    function test_count_counts()
    {
        $this->assertEquals($this->collection->count(), count($this->collection));
    }

    /***********
     * ISEMPTY *
     ***********/

    function test_isEmpty_returns_false()
    {
        $this->assertFalse($this->collection->isEmpty());
    }

    function test_isEmpty_returns_true()
    {
        $collection = new ExampleCollection();
        $this->assertTrue($collection->isEmpty(), "Collection should be empty, isEmpty should return true");
    }

    /*********
     * VALID *
     *********/

    function test_valid_should_return_false()
    {
        $collection = new ExampleCollection();
        $this->assertFalse($collection->valid());
    }

    function test_valid_should_return_true()
    {
        $collection = new ExampleCollection();
        $collection->add($this->items[0]);
        $this->assertTrue($collection->valid());
    }

    /**********
     * REWIND *
     **********/
    function test_rewind()
    {
        $this->collection->next();
        $this->collection->rewind();

        $this->assertEquals(0, $this->collection->key());
    }

    /********
     * NEXT *
     ********/
    function test_next()
    {
        $this->collection->next();
        $this->assertEquals(1, $this->collection->key());
    }

    /*******
     * KEY *
     *******/
    function test_key()
    {
        $this->assertEquals(0, $this->collection->key());
    }

    /***********
     * CURRENT *
     ***********/
    function test_current_returns_theRightKindOfItem()
    {
        $this->assertInstanceOf(ExampleItem::class, $this->collection->current());
    }

    /********
     * EACH *
     ********/
    function test_each_calls_the_callback_on_all_items()
    {
        $publishingLanguages = [];
        $this->collection->each(function ($item) use (&$publishingLanguages) {
            $publishingLanguages[]= $item->name;
        });

        $amountOfOffers = count($this->collection);
        $this->assertCount($amountOfOffers, $publishingLanguages, "should have looped {$amountOfOffers} times");
    }

    /********
     * SPLIT *
     ********/
    function test_split_returnsTheRightAmountOfCollections()
    {
        $result = $this->collection->split(function ($item) {
            return $item->name;
        });

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);

        foreach ($result as $collection) {
            $this->assertInstanceOf(ExampleCollection::class, $collection);
            $this->assertCount(1, $collection);
        }
    }

    /********
     * toArray *
     ********/

    function test_toArray_returns_anArray()
    {
        $result = $this->collection->toArray();

        $this->assertInternalType('array', $result);
        $this->assertCount(count($this->collection), $result);
    }

    /********
     * First *
     ********/
    function test_first_returns_theFirstItemInTheCollection()
    {
        $result = $this->collection->first();

        $this->assertEquals($this->items[0], $result);
    }


    /********
     * Last *
     ********/

    function test_last_returns_theLastItemInTheCollection()
    {
        $result = $this->collection->last();

        $this->assertEquals($this->items[1], $result);
    }

    function test_last_returns_theLastItemInTheCollection_whenCalledTwice()
    {
        $result = $this->collection->last();
        $this->assertEquals($this->items[1], $result);

        $result = $this->collection->last();
        $this->assertEquals($this->items[1], $result, "should always return the same item");
    }
}
