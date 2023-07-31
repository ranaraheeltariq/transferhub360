<?php

namespace App\Interfaces;

interface VehicleRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function delete($id);
    public function create(array $data);
    public function update($id, array $data);
}