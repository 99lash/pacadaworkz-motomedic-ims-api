<?php
namespace App\Services;

use App\Models\Supplier;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class SupplierService
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }
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
        $supplier = Supplier::create($data);
        $this->activityLogService->log('Supplier', 'Created', 'Created supplier: ' . $supplier->name, Auth::id());
        return $supplier;
    }

    public function updateSupplier(array $data, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $oldData = $supplier->toArray();
        $supplier_name = $supplier->name;
        $supplier->update($data);
        $this->activityLogService->log('Supplier', 'Updated', "Update supplier info #{$supplier_name}", Auth::id());
        return $supplier;
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->activityLogService->log('Supplier', 'Deleted', 'Deleted supplier: ' . $supplier->name, Auth::id());
        $supplier->delete();
    }
}
