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

namespace Tenancy\Affects\Filesystem\Events;

use Tenancy\Identification\Events\Switched;

class ConfigureDisk
{
    /**
     * @var Switched
     */
    public $event;
    /**
     * @var array
     */
    public $config;

    public function __construct(Switched $event, array &$config = [])
    {
        $this->event = $event;
        $this->config = &$config;
    }
}
