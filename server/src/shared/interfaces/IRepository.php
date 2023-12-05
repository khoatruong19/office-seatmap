<?php
    declare( strict_types=1 );

    namespace shared\interfaces;

    interface IRepository {
        public function create($data);
        public function findOne(string $field, string $value);
        public function updateOne(string $field, array $data);
        public function delete(string $entity);
    }