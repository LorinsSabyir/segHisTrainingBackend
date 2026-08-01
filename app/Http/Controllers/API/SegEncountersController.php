<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SegApiService;
use Illuminate\Http\JsonResponse;

class SegEncountersController extends Controller
{
    public function __construct(protected SegApiService $segApiService)
    {
    }

    /**
     * GET /api/seg/ward
     */
    public function index(): JsonResponse
    {
        $result = $this->segApiService->getAllEncounter();

        return $this->respond($result);
    }

    /**
     * GET /api/seg/ward/{id}
     */
    public function show(string $id): JsonResponse
    {
        $result = $this->segApiService->getEncounterById($id);

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
