<?php

namespace Fayne\Cascade\Tests\Standalone;

use Fayne\Cascade\CascadeServiceProvider;
use Orchestra\Testbench\TestCase;

class PackageBootTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [CascadeServiceProvider::class];
    }

    public function test_package_registers_its_resources_without_a_host_panel(): void
    {
        $this->assertTrue(view()->exists('filament-cascade::cascade'));
    }
}
