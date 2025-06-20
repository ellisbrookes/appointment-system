<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (File::exists(base_path('.env.testing.local'))) {
            $contents = file_get_contents(base_path('.env.testing.local'));
            foreach (explode("\n", $contents) as $line) {
                if (!empty($line) && str_contains($line, '=')) {
                    putenv(trim($line));
                    [$key, $value] = explode('=', trim($line));
                    config(['database.' . strtolower(explode('_', $key)[1]) => trim($value)]);
                }
            }
        }
    }
}
