<?php

namespace App\Http\Controllers;

use App\Services\CuttingGlnumber\CuttingGlnumberServiceInterface;
use Illuminate\Http\Request;

class CuttingGlNumberController extends Controller
{

    public function __construct(
        private CuttingGlnumberServiceInterface $cuttingGlnumberService,
    ) {}


    public function index(Request $request)
    {
        $filters =  $request->only('gl_number', 'color');


        return $this->cuttingGlnumberService->findBy($filters);
    }
}
