<?php

namespace App;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        $logDir = $this->getProjectDir() . '/var/log';

        // Ensure the directory exists
        if (!is_dir($logDir) && !mkdir($logDir, 0755, true)) {
            throw new RuntimeException(sprintf('Directory "%s" could not be created', $logDir));
        }

        // check if the directory is writable
        if (!is_writable($logDir)) {
            // test for writ-ability by creating a temporary file
            $testFile = $logDir . '/.write_test';
            if (@file_put_contents($testFile, 'test') === false) {
                throw new RuntimeException(sprintf('Directory "%s" is not writable. Try using: "rm -r var/log/*"', $logDir));
            }
            unlink($testFile);
        }

        return $logDir;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        $cacheDir = $this->getProjectDir() . '/var/cache/' . $this->environment;

        // check the directory exists
        if (!is_dir($cacheDir) && !mkdir($cacheDir, 0755, true)) {
            throw new RuntimeException(sprintf('Directory "%s" could not be created', $cacheDir));
        }

        // check if the directory is writable
        if (!is_writable($cacheDir)) {
            // test for writ-ability by creating a temporary file
            $testFile = $cacheDir . '/.write_test';
            if (@file_put_contents($testFile, 'test') === false) {
                throw new RuntimeException(sprintf('Directory "%s" is not writable. Try using: "rm -r var/cache/*"', $cacheDir));
            }
            unlink($testFile);
        }

        return $cacheDir;
    }
}