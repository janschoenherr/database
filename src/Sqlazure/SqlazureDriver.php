<?php

/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Sqlazure;

use Joomla\Database\Sqlsrv\SqlsrvDriver;

/**
 * SQL Azure Database Driver
 *
 * @link   https://msdn.microsoft.com/en-us/library/ee336279.aspx
 * @since  1.0
 */
class SqlazureDriver extends SqlsrvDriver
{
    /**
     * The name of the database driver.
     *
     * @var    string
     * @since  1.0
     */
    public $name = 'sqlazure';
}
