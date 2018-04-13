<?php

namespace Popcorn4dinner\Collection\Tests;

use PHPUnit\Framework\TestCase;

use Popcorn4dinner\Collection\Examples\ExampleItem;
use Popcorn4dinner\Collection\Examples\ExampleJsonSerializableCollection;

class ExampleJsonSerializableCollectionTest extends ExamplePaginatedCollectionTest
{

    private $items;

    function setUp()
    {
        parent::setUp();

        $this->items = [
            new ExampleItem('Fiona'),
            new ExampleItem('Hans'),
            new ExampleItem('Mathias'),
            new ExampleItem('Thomas'),
            new ExampleItem('Helga')
        ];
    }

    function test_fromResponse_returnsACorrectCollection()
    {
        $original = new ExampleJsonSerializableCollection($this->items, 0, 10, 61);
        $result = ExampleJsonSerializableCollection::fromResponseBody(
            $original->jsonSerialize(),
            function ($i) {
                return new ExampleItem($i['name']);
            }
        );

        $this->assertEquals($original, $result);
        $this->assertEquals($original->getCurrentPage(), $result->getCurrentPage());
        $this->assertEquals($original->getLimit(), $result->getLimit());
        $this->assertEquals($original->getOffset(), $result->getOffset());
        $this->assertEquals($original->getTotalAmount(), $result->getTotalAmount());
    }

    function test_jasonSerialize_returns_allMetaData()
    {
        $collection = new ExampleJsonSerializableCollection($this->items, 10, 5, 61);

        $expecredMetaData = [
            "_total_amount"=> 61,
            "_offset"=> 10,
            "_limit"=> 5,
            "_page_size"=> 5,
            "_current_page"=> 3,
            "_amount_pages"=> 13,
            "_has_next_page" => true,
            "_has_previous_page" => true
        ];

        $result = $collection->jsonSerialize();

        foreach ($expecredMetaData as $key => $value) {
            $this->assertTrue(isset($result[$key]), "Expected result to include key {$key}");
            $this->assertEquals($value, $result[$key], "Expected meta info {$key} to be '{$value}' but was '{$result[$key]}'");
        }
    }

    function test_jasonSerialize_returns_allSerializedItems()
    {
        $collection = new ExampleJsonSerializableCollection($this->items, 10, 5, 61);

        $result = $collection->jsonSerialize();

        $this->assertEquals(count($this->items), count($result['items']));
    }
}
