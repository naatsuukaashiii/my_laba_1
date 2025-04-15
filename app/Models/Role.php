<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->using(UserRole::class)
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->using(RolePermission::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (self::where('name', $model->name)->exists()) {
                throw new \Exception('Роль с таким именем уже существует');
            }
            if (self::where('code', $model->code)->exists()) {
                throw new \Exception('Роль с таким шифром уже существует');
            }
        });
    }
}
