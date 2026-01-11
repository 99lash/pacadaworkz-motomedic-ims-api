<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Http\Resources\ActivityLogResource;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController
{
      protected $logservice;
    public function __construct(ActivityLogService $logservice){
        $this->logservice = $logservice;
    }

// show logs
    public function showLogs(Request $request){
      try{
         $search = $request->query('search',null);
          $userId = $request->query('user_id',null);
         $result = $this->logservice->getLogs($search,$userId);

            return response()->json([
            'success' =>true,
            'data' => ActivityLogResource::collection($result),
            'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'total_pages' => $result->lastPage(),
                ],
        ]);
      }catch(\Exception $e){
  return response()->json([
                'success' => false,
                'message' => 'An Error occured'
            ], 401);
      }
    }


// export activity logs
    public function export(){
        $logs = $this->logservice->getExport();

        $response = new StreamedResponse(function() use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'User', 'Module', 'Action', 'Details']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    $log->user->name,
                    $log->module,
                    $log->action,
                    $log->description,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="activity-logs.csv"');

        return $response;
    }
}
