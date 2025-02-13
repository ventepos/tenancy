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

namespace Tenancy\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\Tenant;

class TenantProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->app->bind(Tenant::class, function () {
            /** @var Environment $env */
            $env = resolve(Environment::class);

            return $env->getTenant();
        });
    }

    public function provides()
    {
        return [
             Tenant::class,
         ];
    }
}
