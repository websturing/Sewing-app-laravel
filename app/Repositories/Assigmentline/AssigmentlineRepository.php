<?php

namespace App\Repositories\Assigmentline;

use App\Models\Assigmentline;
use App\Models\LayingPlanning;
use App\Models\Line;
use Illuminate\Support\Carbon;

class AssigmentlineRepository implements AssigmentlineRepositoryInterface
{
    public function all(array $filters)
    {
        $query = Assigmentline::with(['line', 'glnumber', 'layingPlanning'])->get();

        return Line::with(['assignment', 'assignment.line', 'assignment.glnumber', 'assignment.layingPlanning'])->get();
        return $query;
    }

    public function create(array $createData)
    {
        // ambil data assignment_line (exclude laying_planning)
        $assignmentData = collect($createData)->except('laying_planning')->toArray();

        // cek apakah assignment_line sudah ada
        $assignment = Assigmentline::where('gl_id', $assignmentData['gl_id'])
            ->where('line_id', $assignmentData['line_id'])
            ->where('date_start', $assignmentData['date_start'])
            ->where('date_end', $assignmentData['date_end'])
            ->first();

        if (!$assignment) {
            // kalau belum ada, create baru
            $assignment = Assigmentline::create($assignmentData);
        }

        // create/update laying_planning
        if (!empty($createData['laying_planning'])) {
            $exists = LayingPlanning::where('assignment_line_id', $assignment->id)
                ->where('color', $createData['laying_planning']['color'])
                ->where('type', $createData['laying_planning']['type'])
                ->first();

            if (!$exists) {
                LayingPlanning::create([
                    'assignment_line_id' => $assignment->id,
                    'color'              => $createData['laying_planning']['color'],
                    'type'               => $createData['laying_planning']['type'],
                    'order_qty'          => $createData['laying_planning']['summary']['order_qty'],
                    'cut_qty'            => $createData['laying_planning']['summary']['cut_qty'],
                ]);
            } else {
                // optional: kalau mau update qty kalau duplikat color
                $exists->update([
                    'order_qty' => $createData['laying_planning']['summary']['order_qty'],
                    'cut_qty'   => $createData['laying_planning']['summary']['cut_qty'],
                ]);
            }
        }

        return $assignment;
    }



    public function update(int $id, array $updateData)
    {
        $item = Assigmentline::findOrFail($id);
        $item->update($updateData);
        return $item;
    }

    public function delete(int $id)
    {
        $item = Assigmentline::findOrFail($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }
}
