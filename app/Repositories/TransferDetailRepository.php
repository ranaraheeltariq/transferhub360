<?php

namespace App\Repositories;

use App\Interfaces\TransferDetailRepositoryInterface;
use App\Models\TransferDetail;
use Illuminate\Support\Facades\Storage;

class TransferDetailRepository implements TransferDetailRepositoryInterface
{
    private $filePath = 'file/transfer';

    public function getByTransferId($id)
    {
        return TransferDetail::where('transfer_id', $id)->get();
    }
    public function getById($id)
    {
        return TransferDetail::findOrFail($id);
    }
    public function delete($id)
    {
        return TransferDetail::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['file_path'])){
            $path = Storage::putFile($this->filePath, $data['file_path']);
            $data['file_path'] = $path;
        }
        return TransferDetail::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['file_path'])){
            $path = Storage::putFile($this->filePath, $data['file_path']);
            $data['file_path'] = $path;
        }
        return TransferDetail::whereId($id)->update($data);
    }
}