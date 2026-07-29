<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SegApiService;
use Illuminate\Http\JsonResponse;

class SegPatientController extends Controller
{
    public function __construct(protected SegApiService $segApiService)
    {
    }

    /**
     * GET /api/seg/doctors
     */
    public function index(): JsonResponse
    {
        $result = $this->segApiService->getAllPatients();

        return $this->respond($result);
    }

    /**
     * GET /api/seg/Patient/{id}
     */
    public function show(string $id): JsonResponse
    {
        $result = $this->segApiService->getPatientById($id);

        return $this->respond($result);
    }

    /**
     * GET /api/seg/Patient/name/{firstName}/{lastName}
     * Retrieve a specific doctor based on given {name}.
     */
    public function byName(string $firstName, string $lastName): JsonResponse
    {
        $result = $this->segApiService->getPatientByName($lastName, $firstName);

        return $this->respond($result);
    }
    
    /**
     * Shared response formatter for this controller's actions.
     */
    protected function respond(array $result): JsonResponse
    {
        if (! $result['success']) {
            return response()->json([
                'message' => 'Unable to retrieve data from SegService.',
                'error' => $result['error'],
            ], $result['status']);
        }

        return response()->json($result['data']);
    }
}
