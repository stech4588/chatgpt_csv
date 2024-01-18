<?php

namespace App\Models\Role;

use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'permission_id',
        'description',
        'created_by'
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function nameExists($name, $userId)
    {
        return self::where('name', $name)->where('created_by', $userId)->exists();
    }

    public static function exists($id, $userId)
    {
//        return self::where('id', $id)->where('created_by', $userId)->exists();
        return self::where('id', $id)->exists();
    }
    public function getPermissionIdAttribute($value)
    {
        return explode(',', $value);
    }

    public function setPermissionIdAttribute($value)
    {
        $this->attributes['permission_id'] = implode(',', $value);

    }
}
