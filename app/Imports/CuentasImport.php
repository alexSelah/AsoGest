<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CuentasImport implements WithMultipleSheets 
{
    public function sheets(): array
    {
        return [
             0 => new hojaImport(),
        ];
    }
}

