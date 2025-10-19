<?php
// app/Http/Controllers/API/RequestController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Services\ApprovalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    private $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function store(HttpRequest $httpRequest): JsonResponse
    {
        try {
            $data = $httpRequest->validate([
                'amount' => 'required|numeric|min:0',
                'department_id' => 'required|exists:departments,id',
            ]);

            $data['user_id'] = Auth::id();

            $request = $this->approvalService->createRequest($data);

            return response()->json([
                'message' => 'Request created successfully',
                'request' => $request,
                'current_approver' => $request->currentApprover->name,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating request: ' . $e->getMessage()
            ], 400);
        }
    }

    public function approve($id): JsonResponse
    {
        try {
            $request = Request::findOrFail($id);

            // Check if current user is the approver
            if ($request->current_approver_id !== Auth::id()) {
                return response()->json([
                    'message' => 'You are not authorized to approve this request'
                ], 403);
            }

            $updatedRequest = $this->approvalService->approveRequest($id);

            $message = $updatedRequest->status === 'approved'
                ? 'Request fully approved'
                : 'Request approved, moved to next approver';

            return response()->json([
                'message' => $message,
                'request' => $updatedRequest,
                'next_approver' => $updatedRequest->currentApprover->name ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error approving request: ' . $e->getMessage()
            ], 400);
        }
    }

    public function reject($id): JsonResponse
    {
        try {
            $request = Request::findOrFail($id);

            // Check if current user is the approver
            if ($request->current_approver_id !== Auth::id()) {
                return response()->json([
                    'message' => 'You are not authorized to reject this request'
                ], 403);
            }

            $updatedRequest = $this->approvalService->rejectRequest($id);

            return response()->json([
                'message' => 'Request rejected',
                'request' => $updatedRequest,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error rejecting request: ' . $e->getMessage()
            ], 400);
        }
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Get requests where user is requester or current approver
        $requests = Request::with(['user', 'department', 'currentApprover'])
            ->where('user_id', $user->id)
            ->orWhere('current_approver_id', $user->id)
            ->get();

        return response()->json([
            'requests' => $requests
        ]);
    }

    public function show($id): JsonResponse
    {
        $request = Request::with(['user', 'department', 'currentApprover'])
            ->findOrFail($id);

        $user = Auth::user();

        // Check authorization
        if ($request->user_id !== $user->id && $request->current_approver_id !== $user->id) {
            return response()->json([
                'message' => 'Not authorized to view this request'
            ], 403);
        }

        return response()->json([
            'request' => $request
        ]);
    }
}
