<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Resources\StockInResource;
use App\Services\Stockin\StockinServiceInterface;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockIn,
    ) {}

    public function index()
    {

        $items = $this->stockIn
            ->getPaginate(
                request()->all()
            );

        if (!$items) {
            return errorResponse('List Line Not Found', 404);
        }

        return StockInResource::collection($items)->additional([
            'status' => true,
            'message' => 'Succesfully Retrieved StockIns'
        ]);
    }

    public function store(StockInRequest $request)
    {

        $stockInCreate = $this->stockIn->create($request->validated());

        return StockInResource::make($stockInCreate)->additional([
            'status' => true,
            'message' => 'Successfully  Create Stock-in'
        ]);
    }
}
