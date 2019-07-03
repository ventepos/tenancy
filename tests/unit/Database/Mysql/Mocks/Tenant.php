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

namespace Tenancy\Tests\Database\Mysql\Mocks;

use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;
use Tenancy\Identification\Contracts\Tenant as Contract;

class Tenant extends \Tenancy\Testing\Mocks\Tenant implements Contract, ManagesSystemConnection
{
    public function getManagingSystemConnection(): string
    {
        return 'mysql';
    }
}
