<?php

namespace App\Services;

use App\Models\ActivityLog;

class AuditService
{
    public function log(
        string $action,
        ?string $entityType = null,
        ?string $entityId = null,
        ?string $description = null,
        mixed $oldValues = null,
        mixed $newValues = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function logCreate(string $entityType, string $entityId, string $description = null, mixed $newValues = null): ActivityLog
    {
        return $this->log('create', $entityType, $entityId, $description ?? "Created {$entityType}", null, $newValues);
    }

    public function logUpdate(string $entityType, string $entityId, string $description = null, mixed $oldValues = null, mixed $newValues = null): ActivityLog
    {
        return $this->log('update', $entityType, $entityId, $description ?? "Updated {$entityType}", $oldValues, $newValues);
    }

    public function logDelete(string $entityType, string $entityId, string $description = null, mixed $oldValues = null): ActivityLog
    {
        return $this->log('delete', $entityType, $entityId, $description ?? "Deleted {$entityType}", $oldValues, null);
    }

    public function logRoleChange(string $description, mixed $oldValues = null, mixed $newValues = null): ActivityLog
    {
        return $this->log('role_change', 'role', null, $description, $oldValues, $newValues);
    }

    public function logPermissionChange(string $description, mixed $oldValues = null, mixed $newValues = null): ActivityLog
    {
        return $this->log('permission_change', 'permission', null, $description, $oldValues, $newValues);
    }

    public function logSettingsChange(string $description, mixed $oldValues = null, mixed $newValues = null): ActivityLog
    {
        return $this->log('settings_change', 'settings', null, $description, $oldValues, $newValues);
    }

    public function logExport(string $entityType, string $description = null): ActivityLog
    {
        return $this->log('export', $entityType, null, $description ?? "Exported {$entityType}");
    }

    public function logAiUsage(string $description = null, mixed $metadata = null): ActivityLog
    {
        return $this->log('ai_usage', 'ai', null, $description ?? 'AI operation performed', null, $metadata);
    }
}
