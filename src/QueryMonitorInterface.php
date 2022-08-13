<?php

/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database;

/**
 * Interface defining a query monitor.
 *
 * @since  2.0.0
 */
interface QueryMonitorInterface
{
    /**
     * Act on a query being started.
     *
     * @param   string         $sql          The SQL to be executed.
     * @param   object[]|null  $boundParams  List of bound params, used with the query.
     *                                       Each item is an object that holds: value, dataType
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function startQuery(string $sql, ?array $boundParams = null): void;

    /**
     * Act on a query being stopped.
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function stopQuery(): void;
}
