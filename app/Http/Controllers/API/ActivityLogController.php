<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Http\Resources\ActivityLogResource;

class ActivityLogController
{
      protected $logservice;
    public function __construct(ActivityLogService $logservice){
        $this->logservice = $logservice;
    }


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

}
