<?php

namespace App\DTOs\CuttingGLNumber;

use App\DTOs\BaseData;

class FilterDTO extends BaseData
{

    public function __construct(
        public string $glNumber,
        public string $colors,
    ) {}

    public function toFilters(): array
    {
        return [
            'gl_number' => $this->glNumber,
            'colors' => $this->colors
                ? array_map('trim', explode(',', $this->colors))
                : null,
        ];
    }
}
