<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Run default migrations plus the Core Domain package migrations.
     * This method is picked up by the RefreshDatabase trait.
     */
    protected function migrateDatabases(): void
    {
        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        $this->artisan('migrate', [
            '--path' => base_path('vendor/incadev-uns/core-domain/database/migrations'),
            '--force' => true,
        ]);
    }
}
