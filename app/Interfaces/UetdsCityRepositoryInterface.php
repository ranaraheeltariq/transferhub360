<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UetdsCityRepositoryInterface
{
    public function getAll(Request $request);
    public function getById($id);
    public function delete($id);
    public function create(array $data);
    public function update($id, array $data);
}