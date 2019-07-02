<?php declare(strict_types=1);

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

namespace Tenancy\Hooks\Migrations\Hooks;

use Illuminate\Database\Migrations\Migrator;
use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Facades\Tenancy;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Event;

class MigratesHook extends ConfigurableHook
{
    /**
     * @var Migrator
     */
    public $migrator;

    public $connection;

    public $action;

    public $priority = -50;

    public function __construct()
    {
        $this->migrator = resolve('migrator');
        $this->connection = Tenancy::getTenantConnectionName();
    }

    public function for(Event $event)
    {
        $this->action = $event instanceof Deleted ? 'reset' : 'run';

        parent::for($event);

        event(new ConfigureMigrations($event, $this));

        return $this;
    }

    public function fire(): void
    {
        $this->migrator->setConnection($this->connection);

        if (!$this->migrator->repositoryExists()) {
            $this->migrator->getRepository()->createRepository();
        }
        call_user_func([$this->migrator, $this->action], $this->migrator->paths());
    }
}
