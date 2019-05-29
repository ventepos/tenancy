<?php

namespace Tenancy\Database\Hooks;

use Illuminate\Support\Arr;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Lifecycle\Hook;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class DatabaseMutation extends Hook
{
    protected $mapping = [
        Created::class => 'create',
        Updated::class => 'update',
        Deleted::class => 'delete'
    ];
    
    public function fires(): bool
    {
        return Arr::has($this->mapping, get_class($this->event));
    }

    public function fire(): void
    {
        /** @var ResolvesConnections $resolver */
        $resolver = resolve(ResolvesConnections::class);

        $driver = $resolver($this->event->tenant);

        $action = $this->mapping[get_class($this->event)];

        if ($driver && config("tenancy.database.auto-$action")) {
            $driver->{$action}($this->event->tenant);
        }
    }

    public function priority(): int
    {
        return -100;
    }
}
