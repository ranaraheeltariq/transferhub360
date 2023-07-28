<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OwnerRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function delete($id);
    public function create(array $data);
    public function update($id, array $data);
    public function passwordReset(Request $request);
}