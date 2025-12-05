<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAllSuppliers()
    {
        return Supplier::paginate(10);
    }

    public function createSupplier(array $data)
    {
        return Supplier::create($data);
    }

    public function getSupplierById($id)
    {
        return Supplier::findOrfail($id);
    }

    public function updateSupplier(Supplier $supplier, array $data)
    {
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier($id)
    {  
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }
}
