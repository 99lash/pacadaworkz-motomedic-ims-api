<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DummyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dummy",
     *     tags={"Dummy Controller"},
     *     summary="Dummy endpoint",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function dummy()
    {
        return response()->json(['message' => 'This is a dummy endpoint.']);
    }
}
