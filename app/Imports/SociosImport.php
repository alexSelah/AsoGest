<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SociosImport implements WithMultipleSheets 
{
    public function sheets(): array
    {
        return [
             0 => new hojaSociosImport(),
        ];
    }
}

