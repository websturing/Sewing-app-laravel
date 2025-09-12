<?php

namespace App\Services\Glnumber;

use App\Repositories\Glnumber\GlnumberRepositoryInterface;

class GlnumberService implements GlnumberServiceInterface
{
    protected $glnumberRepository;

    public function __construct(GlnumberRepositoryInterface $glnumberRepository)
    {
        $this->glnumberRepository = $glnumberRepository;
    }

    public function getAllGlnumber(array $filters)
    {
        return $this->glnumberRepository->all($filters);
    }

    public function getPaginate(array $filters)
    {
        return $this->glnumberRepository->all($filters)
            ->orderByRaw('CAST(SUBSTRING(gl_number, 6) AS UNSIGNED) ASC')
            ->paginate($filters['per_page'] ?? 1);
    }

    public function findGlNumber(string $glNumber)
    {
        return $this->glnumberRepository->findGlNumber($glNumber);
    }
}
