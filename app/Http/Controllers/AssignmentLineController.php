<?php

namespace App\Http\Controllers;

use App\Services\Assigmentline\AssigmentlineServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\AssignmentLineRequest;

class AssignmentLineController extends Controller
{
    public function __construct(
        private AssigmentlineServiceInterface $assigmentlineService
    ) {
        $this->assigmentlineService = $assigmentlineService;
    }

    public function store(AssignmentLineRequest $request)
    {
        $filters = $request->validated();
        $filters['user_id'] = $request->user()->id;
        $data = $this->assigmentlineService->create($filters);
        return response()->json($data);
    }
}
