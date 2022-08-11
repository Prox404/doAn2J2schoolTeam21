<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdvancedUserImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public function headingRow() : int
    {
        return 1;
    }

    public function collection(Collection $row)
    {
        return ([
            'name' => $row['name'],
            'email' => $row['email'],
            'birthday' => $row['birthday'],
            'password' => Hash::make($row['birthday']),
            'level' => 1,
            'subject' =>  $row['subject'],
        ]);
    }
}
