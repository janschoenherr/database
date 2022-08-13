<?php
/**
 * @copyright  (C) 2022 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\Exception\DatabaseNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for DatabaseAwareTrait class.
 */
class DatabaseAwareTraitTest extends TestCase
{
	/**
	 * @var DatabaseAwareTrait
	 */
	protected $object;

	/**
	 * @testdox  Database can be set with \setDatabase()
	 *
	 * @covers   \Joomla\Database\DatabaseAwareTrait
	 * @uses     \Joomla\Database\Database
	 */
	public function testGetSetDatabase(): void
	{
		$db = $this->createMock(DatabaseInterface::class);

		$trait = new class {
			use DatabaseAwareTrait;

			public function getDb()
			{
				return $this->getDatabase();
			}
		};

		$trait->setDatabase($db);

		$this->assertSame($db, $trait->getDb());
	}

	/**
	 * @testdox  \getDatabase() throws an DatabaseNotFoundException, if no database is set
	 *
	 * @covers   \Joomla\Database\DatabaseAwareTrait
	 */
	public function testGetDatabaseException(): void
	{
		$this->expectException(DatabaseNotFoundException::class);

		$trait = new class {
			use DatabaseAwareTrait;

			public function getDb()
			{
				return $this->getDatabase();
			}
		};

		$trait->getDb();
	}
}
