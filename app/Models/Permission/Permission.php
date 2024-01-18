<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category'
    ];
    public static function nameExists($name)
    {
        return self::where('name', $name)->exists();
    }
    public static function exists($id)
    {
        return self::where('id', $id)->exists();
    }
}
