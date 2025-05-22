<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Auditable
 *
 * @method static creating(\Closure $param)
 * @method static updating(\Closure $param)
 * @method static deleting(\Closure $param)
 */
trait Auditable
{
    /**
     * Boot the Auditable trait for a model.
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->audit('created');
        });

        static::updated(function ($model) {
            $model->audit('updated');
        });

        static::deleted(function ($model) {
            $model->audit('deleted');
        });
    }

    /**
     * Audit the model changes.
     *
     * @param string $event
     */
    public function audit($event)
    {
        if (!in_array($event, ['created', 'updated', 'deleted'])) {
            return;
        }

        $original = $this->getOriginal();
        $changes = $this->getChanges();

        // Don't log if nothing changed
        if ($event === 'updated' && empty($changes)) {
            return;
        }


        // Get the user making the change
        $user = Auth::user();

        $audit = new AuditLog([
            'user_id' => $user ? $user->id : null,
            'user_type' => $user ? get_class($user) : null,
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'old_values' => $event === 'created' ? null : $original,
            'new_values' => in_array($event, ['created', 'updated']) ? $this->toArray() : null,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'tags' => $this->getAuditTags(),
        ]);

        $audit->save();
    }

    /**
     * Get the tags for the audit.
     *
     * @return string|null
     */
    protected function getAuditTags()
    {
        $tags = [];
        
        // Add model name as tag
        $tags[] = Str::snake(class_basename($this));
        
        // Add any additional tags from the model
        if (method_exists($this, 'auditTags')) {
            $tags = array_merge($tags, $this->auditTags());
        }
        
        return implode(',', $tags);
    }
}
