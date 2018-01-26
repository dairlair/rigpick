<?php declare(strict_types = 1);

namespace App\Services;

/**
 * Generate hashes for rigs
 * 
 * @package App\Services
 */
class RigsHashGenerator
{
    private const LENGTH = 6;

    public function generate(): string
    {
        return substr(md5((string) time()), 0, self::LENGTH);
    }
}
