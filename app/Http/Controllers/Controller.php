<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorResponse($message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message]);
    }

    public function successResponse(array $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => true, 'data' => $data]);
    }
}
