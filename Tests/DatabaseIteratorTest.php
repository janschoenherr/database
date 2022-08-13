<?php

/**
 * @copyright  (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license        GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\DatabaseIterator;
use Joomla\Database\FetchMode;
use Joomla\Database\StatementInterface;
use Joomla\Database\Tests\Stubs\TestEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Database\DatabaseIterator
 */
class DatabaseIteratorTest extends TestCase
{
    /**
     * @testdox  The iterator is instantiated and the first object from the result set is set as the current key
     */
    public function testInstantiation()
    {
        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $i = 0;

        $statement->expects($this->any())
            ->method('fetch')
            ->willReturnCallback(
                function () use ($i) {
                    $i++;

                    $object        = new \stdClass();
                    $object->id    = $i;
                    $object->title = 'Row ' . $i;

                    return $object;
                }
            );

        $iterator = new DatabaseIterator($statement);

        $expected        = new \stdClass();
        $expected->id    = 1;
        $expected->title = 'Row 1';

        $this->assertEquals($expected, $iterator->current());
    }

    /**
     * @testdox  The iterator can iterate over all rows in a result set
     */
    public function testIteration()
    {
        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $statement
            ->expects($this->once())
            ->method('setFetchMode')
            ->with(FetchMode::STANDARD_OBJECT);

        $statement
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                (object) ['id' => 1, 'title' => 'Row 1'],
                (object) ['id' => 2, 'title' => 'Row 2'],
                (object) ['id' => 3, 'title' => 'Row 3'],
                (object) ['id' => 4, 'title' => 'Row 4'],
                (object) ['id' => 5, 'title' => 'Row 5'],
                false
            );

        $expected = [
            (object) [
                'id'    => 1,
                'title' => 'Row 1',
            ],
            (object) [
                'id'    => 2,
                'title' => 'Row 2',
            ],
            (object) [
                'id'    => 3,
                'title' => 'Row 3',
            ],
            (object) [
                'id'    => 4,
                'title' => 'Row 4',
            ],
            (object) [
                'id'    => 5,
                'title' => 'Row 5',
            ],
        ];

        $iterator = new DatabaseIterator($statement);

        $this->assertEquals($expected, \iterator_to_array($iterator));
    }

    /**
     * @testdox  The iterator can iterate over all rows in a result set with a custom key
     */
    public function testIterationWithCustomKey()
    {
        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $statement
            ->expects($this->once())
            ->method('setFetchMode')
            ->with(FetchMode::STANDARD_OBJECT);

        $statement
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                (object) ['id' => 1, 'key' => 'Key 1', 'title' => 'Row 1'],
                (object) ['id' => 2, 'key' => 'Key 2', 'title' => 'Row 2'],
                (object) ['id' => 3, 'key' => 'Key 3', 'title' => 'Row 3'],
                (object) ['id' => 4, 'key' => 'Key 4', 'title' => 'Row 4'],
                (object) ['id' => 5, 'key' => 'Key 5', 'title' => 'Row 5'],
                false
            );

        $iterator = new DatabaseIterator($statement, 'key');

        $expected = [
            'Key 1' => (object) [
                'id'    => 1,
                'key'   => 'Key 1',
                'title' => 'Row 1',
            ],
            'Key 2' => (object) [
                'id'    => 2,
                'key'   => 'Key 2',
                'title' => 'Row 2',
            ],
            'Key 3' => (object) [
                'id'    => 3,
                'key'   => 'Key 3',
                'title' => 'Row 3',
            ],
            'Key 4' => (object) [
                'id'    => 4,
                'key'   => 'Key 4',
                'title' => 'Row 4',
            ],
            'Key 5' => (object) [
                'id'    => 5,
                'key'   => 'Key 5',
                'title' => 'Row 5',
            ],
        ];

        $this->assertEquals($expected, \iterator_to_array($iterator));
    }

    /**
     * @testdox  The iterator can iterate over all rows in a result set with a custom PHP class
     */
    public function testIterationWithCustomClass()
    {
        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $statement
            ->expects($this->once())
            ->method('setFetchMode')
            ->with(FetchMode::CUSTOM_OBJECT, TestEntity::class);

        $expected = [];

        for ($i = 1; $i < 6; $i++) {
            $object        = new TestEntity();
            $object->id    = $i;
            $object->title = 'Row ' . $i;

            $expected[] = $object;
        }

        $statement
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                $expected[0],
                $expected[1],
                $expected[2],
                $expected[3],
                $expected[4],
                false
            );

        $iterator = new DatabaseIterator($statement, null, TestEntity::class);

        $this->assertEquals($expected, \iterator_to_array($iterator));
    }

    /**
     * @testdox  The iterator can be counted
     */
    public function testCount()
    {
        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(42);

        $iterator = new DatabaseIterator($statement);

        $this->assertCount(42, $iterator);
    }

    /**
     * @testdox  The iterator cannot be created if the class that objects should be placed in does not exist
     */
    public function testConstructorExceptionForNonExistingClass()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var StatementInterface|MockObject $statement */
        $statement = $this->createMock(StatementInterface::class);

        $iterator = new DatabaseIterator($statement, null, \NonExistingClass::class);
    }
}
