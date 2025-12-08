<?php

namespace App\Dto;

class StockInSummaryReportDTO extends BaseData
{
    public ?string $search;
    public ?string $startDate;
    public ?string $endDate;
    public ?string $glNumber;
    public ?string $color;

    public function __construct(
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $glNumber = null,
        ?string $color = null,
    ) {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->glNumber = $glNumber;
        $this->color = $color;
    }

    public function toFilters(): array
    {
        return array_filter([
            'search' => $this->search,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'gl_number' => $this->glNumber,
            'color' => $this->color,
        ], fn($v) => !is_null($v) && $v !== '');
    }
}
