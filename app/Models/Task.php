<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'created_by',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
