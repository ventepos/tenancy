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

namespace Tenancy\Testing;

use PDO;
use Tenancy\Facades\Tenancy;
use InvalidArgumentException;
use Tenancy\Testing\TestCase;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;

abstract class DatabaseDriverTestCase extends TestCase
{
    protected $db;
    protected $tenant;

    protected function afterSetUp()
    {
        $this->db = resolve(DatabaseManager::class);

        $this->tenant = $this->createMockTenant([
            'id' => 1803,
        ]);
        $this->tenant->unguard();

        $this->resolveTenant($this->tenant);
    }

    protected function getTenantConnection()
    {
        Tenancy::getTenant();

        return $this->db->connection(Tenancy::getTenantConnectionName());
    }

    /**
     * @test
     */
    public function returns_valid_config()
    {
        Tenancy::getTenant();

        $this->assertInstanceOf(
            Connection::class,
            $this->getTenantConnection()
        );
    }

    /**
     * @test
     */
    public function runs_create()
    {
        $this->events->dispatch(new Created($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->db->purge(Tenancy::getTenantConnectionName());
    }

    /**
     * @test
     */
    public function runs_update()
    {
        $this->events->dispatch(new Created($this->tenant));

        $this->tenant->id = 1997;
        $this->events->dispatch(new Updated($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->db->purge(Tenancy::getTenantConnectionName());

        // Not an actual test, but delete the updated tenant for cleaning purposes
        $this->events->dispatch(new Deleted($this->tenant));
    }

    /**
     * @test
     */
    public function prevent_same_update()
    {
        $this->events->dispatch(new Updated($this->tenant));

        $this->expectException(InvalidArgumentException::class);
        $this->getTenantConnection()->getPdo();
    }

    /**
     * @test
     */
    public function runs_delete()
    {
        $this->events->dispatch(new Created($this->tenant));
        $this->events->dispatch(new Deleted($this->tenant));

        $this->expectException(InvalidArgumentException::class);
        $this->getTenantConnection()->getPdo();
    }
}