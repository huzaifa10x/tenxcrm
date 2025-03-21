<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'company_id',
        'img',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->withTimestamps();
    }
    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->pluck('name')->contains($permission);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function salesProjects()
    {
        return $this->belongsToMany(Project::class, 'project_sales_manager');
    }

    public function accountProjects()
    {
        return $this->belongsToMany(Project::class, 'project_account_manager');
    }

    public function techProjects()
    {
        return $this->belongsToMany(Project::class, 'project_tech_manager');
    }
}
