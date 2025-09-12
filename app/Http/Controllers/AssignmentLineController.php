<?php

namespace App\Http\Controllers;

use App\Services\Assigmentline\AssigmentlineServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\AssignmentLineRequest;
use App\Http\Resources\AssignmentLineResource;
use App\Http\Resources\LineAssignmentResource;

class AssignmentLineController extends Controller
{
    public function __construct(
        private AssigmentlineServiceInterface $assigmentlineService
    ) {
        $this->assigmentlineService = $assigmentlineService;
    }

    public function index(Request $request)
    {
        $filters = $request->all();
        $data = $this->assigmentlineService->getAll($filters);
        return  LineAssignmentResource::collection($data)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Assignment Lines',
            // 'code' => 200
        ]);
    }

    public function store(AssignmentLineRequest $request)
    {
        $filters = $request->validated();
        $filters['user_id'] = $request->user()->id;
        $data = $this->assigmentlineService->create($filters);
        return response()->json($data);
    }
}
