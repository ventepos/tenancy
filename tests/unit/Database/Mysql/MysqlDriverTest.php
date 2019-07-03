<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database\Mysql;

use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Testing\DatabaseDriverTestCase;
use Tenancy\Tests\Database\Mysql\Mocks\Tenant;

class MysqlDriverTest extends DatabaseDriverTestCase
{
    protected $additionalProviders = [Provider::class];
    protected $additionalMocks = [__DIR__.'/Mocks/factories/'];
    public $tenantModel = Tenant::class;

    public function afterSetup()
    {
        config(['database.connections.mysql' => include __DIR__.'/database.php']);
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(__DIR__.'/database.php', $event->configuration);
        });
        parent::afterSetUp();
    }
}
