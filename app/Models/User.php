<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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
        'pin_hash',
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
            'notification_preferences' => 'array',
            'pin_set_at' => 'datetime',
            'pin_locked_until' => 'datetime',
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

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function accountProjects()
    {
        return $this->belongsToMany(Project::class, 'project_account_manager');
    }

    public function techProjects()
    {
        return $this->belongsToMany(Project::class, 'project_tech_manager');
    }

    public function managers()
    {
        return $this->belongsToMany(
            User::class,
            'account_manager_leads',
            'am_id',
            'manager_id'
        );
    }

    public function teams()
    {
        return $this->belongsToMany(
            User::class,
            'account_manager_leads',
            'manager_id',
            'am_id'
        );
    }

    /** Email types a user can switch off from their profile. Everything else is always sent. */
    public const OPTIONAL_EMAILS = [
        'daily_digest'  => 'Daily "My Tasks" digest',
        'due_reminders' => 'Due-date reminders (48h and 24h before)',
    ];

    public function wantsEmail(string $type): bool
    {
        if (!array_key_exists($type, self::OPTIONAL_EMAILS)) {
            return true;
        }

        return (bool) (($this->notification_preferences ?? [])[$type] ?? true);
    }

    /** Real person with a usable address (not the system account). */
    public function canReceiveEmail(): bool
    {
        return !empty($this->email)
            && strcasecmp($this->email, \App\Support\ReminderSettings::SYSTEM_EMAIL) !== 0;
    }

    /** True when the user has set a login PIN. */
    public function hasPin(): bool
    {
        return !empty($this->pin_hash);
    }

    /** True while the PIN is locked after too many wrong attempts. */
    public function pinIsLocked(): bool
    {
        return $this->pin_locked_until !== null && $this->pin_locked_until->isFuture();
    }
}
