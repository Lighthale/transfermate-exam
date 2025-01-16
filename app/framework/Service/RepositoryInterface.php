<?php

namespace Framework\Service;

/**
 * Use this to properly implement your Repository classes
 *
 * To know about interface, please refer to the link below:
 * https://www.w3schools.com/php/php_oop_interfaces.asp
 */
interface RepositoryInterface
{
    public function findAll(array $orderBy = [], int $limit = null);
    public function findOneBy(array $field = []);
    public function create();
    public function update();
    public function delete();
}