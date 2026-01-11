<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService{

public function log(
    string $module,
    string $action,
    string $description,
    ?int $userId = null // make it explicitly nullable
) {
    // Use provided userId or fallback to auth()->id()
    $userId = $userId ?? auth()->id();

    //  if still null, skip or throw exception to avoid DB error
    if (!$userId) {
        // Option 1: skip logging
        return;

        // Option 2: throw exception
        // throw new \Exception("Cannot log activity: user_id is null");
    }

    ActivityLog::create([
        'user_id'     => $userId,
        'module'      => $module,
        'action'      => $action,
        'description' => $description,
        'ip_address'  => request()->ip(),
        'user_agent'  => request()->userAgent(),
    ]);
}

public function getLogs(?string $search = null,?int $userId = null)
{
    // Start query with user relationship
    $query = ActivityLog::with('user');

    // Apply search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('module', 'ILIKE', "%{$search}%")
              ->orWhere('action', 'ILIKE', "%{$search}%")
              ->orWhere('description', 'ILIKE', "%{$search}%")
              ->orWhere('id','ILIKE', "%{$search}%")
              ->orWhereHas('user', function($q2) use ($search) {
                  $q2->where('name', 'ILIKE', "%{$search}%");
              });
        });
    }else if($userId){
         $query->where('user_id', $userId);
    }

    return $query->orderBy('created_at', 'desc')->paginate(10);
}

   
}