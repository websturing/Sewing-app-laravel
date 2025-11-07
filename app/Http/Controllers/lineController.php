<?php

namespace App\Http\Controllers;

use App\Http\Resources\LineResource;
use App\Models\Line;
use App\Services\Line\LineServiceInterface;
use Illuminate\Http\Request;

class lineController extends Controller
{

    public function __construct(
        private LineServiceInterface $lineService,
    ) {}

    public function index(Request $request)
    {


        $lines = $this->lineService
            ->linesWithLastGlTransactions(
                $request->all()
            );

        if (!$lines) {
            return errorResponse('List Line Not Found', 404);
        }



        return $collection = LineResource::collection($lines)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Lines'
        ]);
    }

    public function getById(Request $request, $lineId)
    {
        $filters = array_merge($request->all(), ['line_id' => $lineId]);

        $line = $this->lineService->getById($filters);

        if (!$line) {
            return errorResponse('Line Not Found', 404);
        }

        return (new LineResource($line))->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Line'
        ]);
    }
}
