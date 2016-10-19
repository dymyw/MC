<?php

namespace App\Controller\Plugin;

class Max
{
    /**
     * Get the bigger one
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function __invoke($a, $b)
    {
        return $a >= $b ? $a : $b;
    }
}
