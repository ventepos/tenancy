<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database;

use Tenancy\Testing\DatabaseDriverTestCase;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Database\Drivers\Mysql\Providers\ServiceProvider;

class MysqlDriverTest extends DatabaseDriverTestCase
{
    protected $additionalProviders = [ServiceProvider::class];
    protected $additionalMocks = [__DIR__ . '/Mocks/factories/'];

    public function afterSetup()
    {
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(__DIR__ . '/database.php', $event->configuration);
        });
        parent::afterSetUp();
    }
}
