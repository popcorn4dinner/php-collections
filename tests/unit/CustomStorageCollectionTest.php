<?php

namespace StepStone\SeedCommons\Tests\Collection;



use PHPUnit\Framework\TestCase;
use StepStone\SeedCommons\Examples\Collection\CustomStorageCollection;
use StepStone\SeedCommons\Examples\Collection\ExampleCollection;
use StepStone\SeedCommons\Examples\Collection\ExampleItem;

class CustomStorageCollectionTest extends TestCase
{
    private $collection;
    private $items = [];

    function setUp()
    {
        parent::setUp();

        $this->items[]= new ExampleItem('Fiona');
        $this->items[]= new ExampleItem('Hans');
        $this->items[]= new ExampleItem('Helga');


        $this->collection = new CustomStorageCollection();
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

    function test_that_reject_reduces_the_offers_in_collection(){

        $result = $this->collection->reject(function($item){
            return $item->name === 'Hans';
        });

        $this->assertEquals(1, count($result));
    }

    function test_that_reject_does_not_reduce_the_offers_in_collection(){
        $result = $this->collection->reject(function(){
            return false;
        });

        $before = count($this->collection);
        $after = count($result);
        $this->assertTrue($before === $after);
    }

    function test_reject_adds_returns_itself()
    {
        $this->assertInstanceOf(
            CustomStorageCollection::class,
            $this->collection->reject(function(){return true;})
        );
    }

    /**********
     * FILTER *
     **********/

    function test_that_filter_reduces_the_offers_in_collection(){
        $result = $this->collection->filter(function($item){
            return $item->name === 'Hans';
        });

        $this->assertEquals(1 , count($result));
    }

    function test_that_filter_does_not_reduce_the_offers_in_collection(){
        $result = $this->collection->filter(function(){
            return false;
        });

        $before = count($this->collection);
        $after = count($result);
        $this->assertTrue($before > $after);
    }

    function test_filter_adds_returns_itself()
    {
        $this->assertInstanceOf(
            CustomStorageCollection::class,
            $this->collection->filter(function(){return true;})
        );
    }

    /*********
     * COUNT *
     *********/

    function test_count_counts()
    {
        $this->assertEquals($this->collection->count(),count($this->collection));
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
        $collection = new CustomStorageCollection();
        $this->assertTrue($collection->isEmpty(), "Collection should be empty, isEmpty should return true");
    }

    /*********
     * VALID *
     *********/

    function test_valid_should_return_false()
    {
        $collection = new CustomStorageCollection();
        $this->assertFalse($collection->valid());
    }

    function test_valid_should_return_true()
    {
        $collection = new CustomStorageCollection();
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

        $this->assertEquals('Fiona', $this->collection->key());
    }

    /********
     * NEXT *
     ********/
    function test_next()
    {
        $this->collection->next();
        $this->assertEquals('Hans', $this->collection->key());
    }

    /*******
     * KEY *
     *******/
    function test_key()
    {
        $this->assertEquals('Fiona', $this->collection->key());
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
        $this->collection->each(function($item) use (&$publishingLanguages)
        {
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
        $result = $this->collection->split(function($item)
        {
            return $item->name;
        });

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);

        foreach ($result as $collection){
            $this->assertInstanceOf(CustomStorageCollection::class, $collection);
            $this->assertCount(1, $collection);
        }
    }




}
