<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Http\Resources\ActivityLogResource;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController
{
    protected $logservice;

    public function __construct(ActivityLogService $logservice)
    {
        $this->logservice = $logservice;
    }

    // show logs
    public function showLogs(Request $request)
    {
        try {
            // OPTIONAL: kung may policy
            // $this->authorize('viewAny', ActivityLog::class);

            $search = $request->query('search');
            $userId = $request->query('user_id');

            $result = $this->logservice->getLogs($search, $userId);

            return response()->json([
                'success' => true,
                'data' => ActivityLogResource::collection($result),
                'meta' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'total_pages' => $result->lastPage(),
                ],
            ]);

        } catch (AuthorizationException $e) {
            //  REAL 403
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);

        } catch (\Throwable $e) {
            //  REAL server error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                 'error' => $e->getMessage() // enable lang sa debug
            ], 500);
        }
    }

    // export activity logs
    public function export()
    {
        // OPTIONAL AUTH
        // $this->authorize('export', ActivityLog::class);

        $logs = $this->logservice->getExport();

        $response = new StreamedResponse(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'User', 'Module', 'Action', 'Details']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    optional($log->user)->name,
                    $log->module,
                    $log->action,
                    $log->description,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="activity-logs.csv"'
        );

        return $response;
    }
}
