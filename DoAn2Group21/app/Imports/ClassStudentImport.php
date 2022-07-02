<?php

namespace App\Imports;

use App\Models\ClassStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClassStudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function headingRow() : int
    {
        return 1;
    }

    public function model(array $row)
    {
        return new ClassStudent([
            'user_id' => $row['user_id'],
            'class_id' => $row['class_id']
        ]);
    }
}
