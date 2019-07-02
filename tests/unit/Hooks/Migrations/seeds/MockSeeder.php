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

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockSeeder extends Seeder
{
    public function run()
    {
        DB::table('mocks')->insert([
            'id' => 5, 'name' => 'test'
        ]);
    }
}
