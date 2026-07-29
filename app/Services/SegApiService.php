<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SegApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $username;
    protected $password;
    protected $verifySsl;

    public function __construct()
    {
        $this->baseUrl = config('services.seg.base_url');
        $this->timeout = config('services.seg.timeout', 60);
        $this->username = config('services.seg.username');
        $this->password = config('services.seg.password');
        $this->verifySsl = config('services.seg.verify_ssl', true);
    }

    /**
     * Make a GET request to the SEG API
     */
    protected function get($endpoint, $params = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withOptions([
                    'verify' => $this->verifySsl,
                ])
                ->withBasicAuth($this->username, $this->password)
                ->get($this->baseUrl . $endpoint, $params);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('SEG API Error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Make a POST request to the SEG API
     */
    protected function post($endpoint, $data = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withOptions([
                    'verify' => $this->verifySsl,
                ])
                ->withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . $endpoint, $data);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('SEG API Error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Handle API response
     */
    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    /**
     * Error response
     */
    protected function errorResponse($message)
    {
        return [
            'success' => false,
            'error' => $message,
            'status' => 500,
        ];
    }

    // ============================================
    // DOCTOR ENDPOINTS
    // ============================================

    /**
     * Retrieve all doctors
     * GET /doctor/show/
     */
    public function getAllDoctors()
    {
        return $this->get('/doctor/show/');
    }

    /**
     * Retrieve doctor by ID
     * GET /doctor/show/id/{id}
     */
    public function getDoctorById($id)
    {
        return $this->get('/doctor/show/id/' . $id);
    }

    /**
     * Retrieve doctors by department
     * GET /doctor/show/deptid/{deptid}
     */
    public function getDoctorsByDepartment($deptId)
    {
        return $this->get('/doctor/show/deptid/' . $deptId);
    }

    /**
     * Retrieve doctor by name
     * GET /doctor/show/name_first/{first}/name_last/{last}
     */
    public function getDoctorByName($firstName, $lastName)
    {
        return $this->get('/doctor/show/name_first/' . $firstName . '/name_last/' . $lastName);
    }

    /**
     * Retrieve doctor notes as image
     * GET /doctor/notes/type/{type}/doctor_nr/{doctor_nr}/encounter_nr/{encounter_nr}
     */
    public function getDoctorNotes($type, $doctorNr, $encounterNr)
    {
        return $this->get('/doctor/notes/type/' . $type . '/doctor_nr/' . $doctorNr . '/encounter_nr/' . $encounterNr);
    }

    /**
     * Add doctor notes as image
     * POST /doctor/createnote
     */
    public function createDoctorNote($data)
    {
        return $this->post('/doctor/createnote', $data);
    }

    // ============================================
    // NURSE ENDPOINTS
    // ============================================

    /**
     * Retrieve all nurses
     * GET /nurse/show/
     */
    public function getAllNurses()
    {
        return $this->get('/nurse/show/');
    }

    /**
     * Retrieve nurse by ID
     * GET /nurse/show/id/{id}
     */
    public function getNurseById($id)
    {
        return $this->get('/nurse/show/id/' . $id);
    }

    /**
     * Retrieve nurses by department
     * GET /nurse/show/deptid/{deptid}
     */
    public function getNursesByDepartment($deptId)
    {
        return $this->get('/nurse/show/deptid/' . $deptId);
    }

    /**
     * Retrieve nurse by name
     * GET /nurse/show/name_first/{first}/name_last/{last}
     */
    public function getNurseByName($firstName, $lastName)
    {
        return $this->get('/nurse/show/name_first/' . $firstName . '/name_last/' . $lastName);
    }

    // ============================================
    // PATIENTS ENDPOINTS
    // ============================================

    /**
     * Retrieve all Patients
     * GET /patient/show/
     */
    public function getAllPatients()
    {
        return $this->get('/patient/show/');
    }

    /**
     * Retrieve Patient by ID
     * GET /patient/show/id/{id}
     */
    public function getPatientById($id)
    {
        return $this->get('/patient/show/id/' . $id);
    }

    /**
     * Retrieve patient by name
     * GET /patient/show/name_first/{first}/name_last/{last}
     */
    public function getPatientByName($firstName, $lastName)
    {
        return $this->get('/patient/show/name_last/' . $lastName . '/name_first/' . $firstName);
    }
}