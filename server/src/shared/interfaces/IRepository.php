<?php

namespace shared\interfaces;

interface IRepository {
    public function save($entity);
    public function findOne(string $field, string $value);
}