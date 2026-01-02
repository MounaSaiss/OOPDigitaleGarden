<?php
interface RepositoryInterface{
    public function __construct(PDO $conn, int $idUser);
    public function findAll();
    public function find(int $id);
    public function add(object $entity);
    public function update(object $entity);
    public function delete(int $id);
}