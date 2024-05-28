<?php

namespace system\DI;

interface ContainerInterface
{
    /**
     * @param string $id
     * @return object
     */
    public  function get(string $id): object;

    /**
     * @param string $id
     * @return bool
     */
    public  function has(string $id): bool;
}