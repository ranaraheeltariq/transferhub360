<?php

namespace App\Interfaces;

interface TransferDetailRepositoryInterface
{
    public function getByTransferId($id);
    public function getById($id);
    public function delete($id);
    public function create(array $data);
    public function update($id, array $data);
}