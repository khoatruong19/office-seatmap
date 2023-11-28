<?php

namespace shared\interfaces;

interface IRepository {
    public function save($data);
    public function findOne(string $field, string $value);
    // public function update(string $field, string $value);
    // public function delete(string $entity);
}