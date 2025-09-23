<?php

namespace App\Repositories\Stockin;

use App\Models\Stockin;

class StockinRepository implements StockinRepositoryInterface
{
    public function all()
    {
        return Stockin::all();
    }


    public function create(array $data): Stockin
    {

        return Stockin::create($data);
    }

    public function update(int $id, array $data): Stockin
    {
        $record = StockIn::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id): ?Stockin
    {
        $record = StockIn::find($id);

        if (!$record) {
            return null;
        }
        $deletedRecord = $record->replicate;
        $record->delete();

        return $deletedRecord;
    }

    public function paginateAll(array $filters)
    {
        $query = Stockin::query();

        $query->when(
            $filters['q'] ?? null,
            fn($q, $query) => $q->where('gl_no', 'LIKE', "%{$query}%")
                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                ->orWhere('color', 'LIKE', "%{$query}%")
        );



        return $query;
    }
}
