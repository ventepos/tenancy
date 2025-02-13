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

namespace Tenancy\Database\Events\Drivers;

use InvalidArgumentException;
use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Identification\Contracts\Tenant;

class Configuring
{
    /**
     * @var Tenant
     */
    public $tenant;
    /**
     * @var array
     */
    public $configuration;
    /**
     * @var ProvidesDatabase
     */
    public $provider;

    public function __construct(Tenant $tenant, array &$configuration, ProvidesDatabase $provider)
    {
        $configuration = $this->defaults($tenant, $configuration);

        $this->tenant = $tenant;
        $this->configuration = &$configuration;
        $this->provider = $provider;
    }

    public function useConnection(string $connection, array $override = [])
    {
        $this->configuration = array_merge(
            config("database.connections.$connection"),
            $override
        );

        return $this;
    }

    public function useConfig(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Cannot set up tenant connection configuration, file $path does not exist.");
        }

        $this->configuration = include $path;

        return $this;
    }

    protected function defaults(Tenant $tenant, array &$configuration): array
    {
        if ($tenant->isDirty($tenant->getTenantKeyName())) {
            $configuration['oldUsername'] = $tenant->getOriginal($tenant->getTenantKeyName());
        }

        $configuration['username'] = $configuration['username'] ?? $tenant->getTenantKey();
        $configuration['database'] = $configuration['database'] ?? $configuration['username'];
        $configuration['password'] = $configuration['password'] ?? resolve(ProvidesPassword::class)->generate($tenant);

        return $configuration;
    }
}
