<?php

namespace Tests;

use Sarahman\SimpleCache\FileSystemCache;

class FileSystemCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_checks_data_clearing_from_cache()
    {
        $cache = new FileSystemCache();
        $cache->clear();

        $this->assertEmpty($cache->all());
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function it_checks_data_existence_in_cache()
    {
        $cache = new FileSystemCache();
        $cache->clear();

        $this->assertFalse($cache->has('custom_key'));

        $cache->set('custom_key', 'sample data');
        $this->assertTrue($cache->has('custom_key'));

        // Set Cache key.
        $cache->delete('custom_key');
        $this->assertFalse($cache->has('custom_key'));
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function it_checks_data_stored_in_temporary_files_directory()
    {
        $cache = new FileSystemCache();

        $this->assertInstanceOf('Sarahman\SimpleCache\FileSystemCache', $cache);

        $cache->clear();

        $this->assertFalse($cache->has('custom_key'));

        // Set Cache key.
        $cache->set('custom_key', [
            'sample' => 'data',
            'another' => 'data'
        ]);

        $this->assertTrue($cache->has('custom_key'));

        // Get Cached key data.
        $this->assertArrayHasKey('sample', $cache->get('custom_key'));
        $this->assertArrayHasKey('another', $cache->get('custom_key'));
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function it_checks_data_stored_in_the_given_directory()
    {
        $cache = new FileSystemCache('tmp');

        $this->assertInstanceOf('Sarahman\SimpleCache\FileSystemCache', $cache);

        $cache->clear();

        $this->assertFalse($cache->has('custom_key'));

        // Set Cache key.
        $cache->set('custom_key', [
            'sample' => 'data',
            'another' => 'data'
        ]);

        $this->assertTrue($cache->has('custom_key'));

        // Get Cached key data.
        $this->assertArrayHasKey('sample', $cache->get('custom_key'));
        $this->assertArrayHasKey('another', $cache->get('custom_key'));
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function it_checks_data_removal_when_its_lifetime_of_caching_passed()
    {
        $cache = new FileSystemCache();

        // Set Cache key.
        $cache->set('some_data', 'to be removed', 5);
        $this->assertTrue($cache->has('some_data'));

        sleep(6);
        $this->assertFalse($cache->has('some_data'));
    }

    /**
     * @test
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function it_checks_cached_data_lifetime_incrementing()
    {
        $cache = new FileSystemCache();

        $cache->set('some_data', 'to be touched', 5);
        $this->assertTrue($cache->has('some_data'));

        $cache->touch('some_data', 5);
        $this->assertTrue($cache->has('some_data'));

        sleep(6);
        $this->assertFalse($cache->touch('some_data', 10));
    }
}
