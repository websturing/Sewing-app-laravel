<?php

namespace App\Http\Controllers;

use App\Services\Stockin\StockinServiceInterface;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function __construct(
        private StockinServiceInterface $stockIn,
    ) {}

    public function index()
    {

        return $items = $this->stockIn
            ->getPaginate(
                request()->all()
            );

        // if (!$lines) {
        //     return errorResponse('List Line Not Found', 404);
        // }

        // return $collection = LineResource::collection($lines)->additional([
        //     'status' => true,
        //     'message' => 'Succesfully Retrieved Lines'
        // ]);
    }
}
