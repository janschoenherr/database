<?php
/**
 * @copyright  (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseExporter;
use Joomla\Database\DatabaseFactory;
use Joomla\Database\DatabaseImporter;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\DatabaseIterator;
use Joomla\Database\Exception\UnsupportedAdapterException;
use Joomla\Database\Mysqli\MysqliDriver;
use Joomla\Database\QueryInterface;
use Joomla\Database\StatementInterface;
use Joomla\Test\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Database\DatabaseFactory
 */
class DatabaseFactoryTest extends TestCase
{
	/**
	 * Object being tested
	 *
	 * @var  DatabaseFactory
	 */
	private $factory;

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->factory = new DatabaseFactory;
	}

	/**
	 * Data provider for driver test cases
	 *
	 * @return  \Generator
	 */
	public function dataGetDriver(): \Generator
	{
		yield 'supported driver' => [
			'mysqli',
			false,
		];

		yield 'unsupported exporter' => [
			'mariadb',
			true,
		];
	}

	/**
	 * @testdox  The factory builds a database driver correctly
	 *
	 * @param   string   $adapter               The type of adapter to create
	 * @param   boolean  $shouldRaiseException  Flag indicating the factory should raise an exception for an unsupported adapter
	 *
	 * @dataProvider  dataGetDriver
	 */
	public function testGetDriver(string $adapter, bool $shouldRaiseException)
	{
		if ($shouldRaiseException)
		{
			$this->expectException(UnsupportedAdapterException::class);
		}

		$this->assertInstanceOf(
			DatabaseInterface::class,
			$this->factory->getDriver($adapter)
		);
	}

	/**
	 * Data provider for exporter test cases
	 *
	 * @return  \Generator
	 */
	public function dataGetExporter(): \Generator
	{
		yield 'exporter without database driver' => [
			'mysqli',
			false,
			null,
		];

		yield 'exporter with database driver' => [
			'mysqli',
			false,
			$this->createMock(MysqliDriver::class),
		];

		yield 'unsupported exporter' => [
			'mariadb',
			true,
			null,
		];
	}

	/**
	 * @testdox  The factory builds a database exporter correctly
	 *
	 * @param   string               $adapter               The type of adapter to create
	 * @param   boolean              $shouldRaiseException  Flag indicating the factory should raise an exception for an unsupported adapter
	 * @param   DatabaseDriver|null  $databaseDriver        The optional database driver to be injected into the exporter
	 *
	 * @dataProvider  dataGetExporter
	 */
	public function testGetExporter(string $adapter, bool $shouldRaiseException, ?DatabaseDriver $databaseDriver)
	{
		if ($shouldRaiseException)
		{
			$this->expectException(UnsupportedAdapterException::class);
		}

		$exporter = $this->factory->getExporter($adapter, $databaseDriver);

		$this->assertInstanceOf(
			DatabaseExporter::class,
			$exporter
		);

		if ($databaseDriver)
		{
			$this->assertSame(
				TestHelper::getValue($exporter, 'db'),
				$databaseDriver
			);
		}
	}

	/**
	 * Data provider for importer test cases
	 *
	 * @return  \Generator
	 */
	public function dataGetImporter(): \Generator
	{
		yield 'importer without database driver' => [
			'mysqli',
			false,
			null,
		];

		yield 'importer with database driver' => [
			'mysqli',
			false,
			$this->createMock(MysqliDriver::class),
		];

		yield 'unsupported importer' => [
			'mariadb',
			true,
			null,
		];
	}

	/**
	 * @testdox  The factory builds a database importer correctly
	 *
	 * @param   string               $adapter               The type of adapter to create
	 * @param   boolean              $shouldRaiseException  Flag indicating the factory should raise an exception for an unsupported adapter
	 * @param   DatabaseDriver|null  $databaseDriver        The optional database driver to be injected into the importer
	 *
	 * @dataProvider  dataGetImporter
	 */
	public function testGetImporter(string $adapter, bool $shouldRaiseException, ?DatabaseDriver $databaseDriver)
	{
		if ($shouldRaiseException)
		{
			$this->expectException(UnsupportedAdapterException::class);
		}

		$importer = $this->factory->getImporter($adapter, $databaseDriver);

		$this->assertInstanceOf(
			DatabaseImporter::class,
			$importer
		);

		if ($databaseDriver)
		{
			$this->assertSame(
				TestHelper::getValue($importer, 'db'),
				$databaseDriver
			);
		}
	}

	/**
	 * Data provider for iterator test cases
	 *
	 * @return  \Generator
	 */
	public function dataGetIterator(): \Generator
	{
		yield 'driver without custom iterator' => [
			'mysqli',
			$this->createMock(StatementInterface::class),
		];
	}

	/**
	 * @testdox  The factory builds a database iterator correctly
	 *
	 * @param   string              $adapter    The type of adapter to create
	 * @param   StatementInterface  $statement  Statement holding the result set to be iterated.
	 *
	 * @dataProvider  dataGetIterator
	 */
	public function testGetIterator(string $adapter, StatementInterface $statement)
	{
		$this->assertInstanceOf(
			DatabaseIterator::class,
			$this->factory->getIterator($adapter, $statement)
		);
	}

	/**
	 * Data provider for query test cases
	 *
	 * @return  \Generator
	 */
	public function dataGetQuery(): \Generator
	{
		yield 'supported query' => [
			'mysqli',
			false,
			$this->createMock(MysqliDriver::class),
		];

		yield 'unsupported query' => [
			'mariadb',
			true,
			null,
		];
	}

	/**
	 * @testdox  The factory builds a database query object correctly
	 *
	 * @param   string               $adapter               The type of adapter to create
	 * @param   boolean              $shouldRaiseException  Flag indicating the factory should raise an exception for an unsupported adapter
	 * @param   DatabaseDriver|null  $databaseDriver        The optional database driver to be injected into the importer
	 *
	 * @dataProvider  dataGetQuery
	 */
	public function testGetQuery(string $adapter, bool $shouldRaiseException, ?DatabaseDriver $databaseDriver)
	{
		if ($shouldRaiseException)
		{
			$this->expectException(UnsupportedAdapterException::class);
		}

		$this->assertInstanceOf(
			QueryInterface::class,
			$this->factory->getQuery($adapter, $databaseDriver)
		);
	}
}
