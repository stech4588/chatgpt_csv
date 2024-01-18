<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData1 extends Model
{
    use HasFactory;
    protected $table = 'csv_data1';

    protected $fillable = [
        'col',
        'row',
        'prompt',
        'data',
        'file_path',
        'created_by'
    ];
}
