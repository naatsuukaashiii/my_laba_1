<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Permission extends Model
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
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_and_permissions')
            ->withPivot('created_at', 'created_by', 'deleted_at', 'deleted_by')
            ->withTimestamps();
    }
}