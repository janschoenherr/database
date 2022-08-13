<?php

/**
 * Part of the Joomla Framework Database Package
 *
 * @copyright  (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Service;

use Joomla\Registry\Registry;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseFactory;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Database service provider
 *
 * @since  2.0.0
 */
class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   2.0.0
     */
    public function register(Container $container)
    {
        $container->alias(DatabaseInterface::class, DatabaseDriver::class)
            ->share(
                DatabaseDriver::class,
                function (Container $container) {
                    /** @var Registry $config */
                    $config  = $container->get('config');
                    $options = (array) $config->get('database');

                    return $container->get(DatabaseFactory::class)->getDriver($options['driver'], $options);
                }
            );

        $container->share(
            DatabaseFactory::class,
            function (Container $container) {
                return new DatabaseFactory();
            }
        );
    }
}
