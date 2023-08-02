<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use Illuminate\Support\Facades\Storage;

class TransferRepository implements TransferRepositoryInterface
{
    public function getAll()
    {
        return Transfer::paginate(10);
    }
    public function getById($id)
    {
        return Transfer::findOrFail($id);
    }
    public function delete($id)
    {
        return Transfer::destroy($id);
    }
    public function create(array $data)
    {
        return Transfer::create($data);
    }
    public function update($id, array $data)
    {
        return Transfer::whereId($id)->update($data);
    }
}