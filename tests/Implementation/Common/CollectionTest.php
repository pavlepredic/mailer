<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testContains()
    {
        $collection = new Collection();
        $collection->add(Factory::createHeader('header1', 'value1'));
        $collection->add(Factory::createHeader('header2', 'value2'));

        $this->assertTrue($collection->contains(Factory::createHeader('header1', 'value1')));
        $this->assertTrue($collection->contains(Factory::createHeader('header2', 'value2')));
        $this->assertFalse($collection->contains(Factory::createHeader('header3', 'value3')));
    }


    public function testEquality()
    {
        $collection1 = new Collection();
        $collection1->add(Factory::createHeader('header1', 'value1'));
        $collection1->add(Factory::createHeader('header2', 'value2'));

        $collection2 = new Collection();
        $collection2->add(Factory::createHeader('header2', 'value2'));
        $collection2->add(Factory::createHeader('header1', 'value1'));

        $this->assertTrue($collection1->equals($collection2));

        $collection2->add(Factory::createHeader('header3', 'value3'));
        $this->assertFalse($collection1->equals($collection2));

        $collection2->removeElement(Factory::createHeader('header3', 'value3'));
        $this->assertTrue($collection1->equals($collection2));

        $collection2->removeElement(Factory::createHeader('header2', 'value2'));
        $this->assertFalse($collection1->equals($collection2));
    }
}
