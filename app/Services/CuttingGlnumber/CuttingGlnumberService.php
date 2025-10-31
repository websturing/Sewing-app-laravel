<?php

namespace App\Services\CuttingGlnumber;

use App\DTOs\CuttingGLNumber\FilterDTO;
use App\Repositories\CuttingGlnumber\CuttingGlnumberRepositoryInterface;

class CuttingGlnumberService implements CuttingGlnumberServiceInterface
{
    protected $cuttingGlnumberRepository;

    public function __construct(CuttingGlnumberRepositoryInterface $cuttingGlnumberRepository)
    {
        $this->cuttingGlnumberRepository = $cuttingGlnumberRepository;
    }

    public function getAllCuttingGlnumber($filters)
    {


        $glNumber = $filters['gl_number'] ?? null;
        $color = $filters['color'] ?? null;

        if ($glNumber && $color) {
            $items = $this->cuttingGlnumberRepository->allWithSearching($glNumber, $color);
            return [
                "status" => $items !== null, // kalau pakai Collection
                "data" => $items
            ];
        } else {
            return $this->cuttingGlnumberRepository->all($glNumber, $color);
        }
    }

    public function findBy(FilterDTO $dto)
    {

        $filters =  $dto->toFilters();
        $glNumber = $filters['gl_number'] ?? null;
        $colors = $filters['colors'] ?? null;



        $items = $this->cuttingGlnumberRepository->allWithSearching($glNumber, $colors);

        return [
            "isFound" =>  $items ? true : false,
            "data" => $items
        ];
    }
}
