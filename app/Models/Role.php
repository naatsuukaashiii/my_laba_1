<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'code',
        'created_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_and_roles')
            ->withPivot('created_at', 'created_by', 'deleted_at', 'deleted_by')
            ->withTimestamps();
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_and_permissions')
            ->withPivot('created_at', 'created_by', 'deleted_at', 'deleted_by')
            ->withTimestamps();
    }
}