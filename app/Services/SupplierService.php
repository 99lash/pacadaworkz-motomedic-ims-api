<?php
namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAllSuppliers($search = null)
    {
        $query = Supplier::query();

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate(10)->withQueryString();
    }

    public function getSupplierById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function createSupplier(array $data)
    {
        return Supplier::create($data);
    }

    public function updateSupplier(array $data, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }
}
