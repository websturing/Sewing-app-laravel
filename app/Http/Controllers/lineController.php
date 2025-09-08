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

    public function index()
    {

        $lines = $this->lineService
            ->getLinePaginate(
                request()->all()
            );

        if (!$lines) {
            return errorResponse('List Line Not Found', 404);
        }

        return $collection = LineResource::collection($lines)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved Lines'
        ]);
    }
}
