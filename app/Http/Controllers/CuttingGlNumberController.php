<?php

namespace App\Http\Controllers;

use App\DTOs\CuttingGLNumber\FilterDTO;
use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use Illuminate\Http\Request;

class CuttingGlNumberController extends Controller
{

    public function __construct(
        private CuttingGlnumberServiceInterface $cuttingGlnumberService,
    ) {}


    public function index(Request $request)
    {
        $filters = FilterDTO::fromQuery($request);
        return $this->cuttingGlnumberService->findBy($filters);
    }
}
