<?php

namespace App\Traits;

use App\Models\SystemAudit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditAction('CREATED');
        });

        static::updated(function ($model) {
            $model->auditAction('UPDATED');
        });

        static::deleted(function ($model) {
            $model->auditAction('DELETED');
        });
    }

    protected function auditAction(string $action)
    {
        // Só registra log caso exista um terminal conectado e IP ativo na Session/Request.
        // Pula Seeds ou Comandos do Console crús
        if (!app()->runningInConsole() && Auth::check()) {
            
            $oldValues = [];
            $newValues = [];

            if ($action === 'UPDATED') {
                $oldValues = array_intersect_key($this->getOriginal(), $this->getDirty());
                $newValues = $this->getDirty();
            } elseif ($action === 'CREATED') {
                $newValues = $this->getAttributes();
            } elseif ($action === 'DELETED') {
                $oldValues = $this->getAttributes();
            }

            SystemAudit::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'auditable_type' => get_class($this),
                'auditable_id' => $this->id ?? 0,
                'old_values' => empty($oldValues) ? null : json_encode($oldValues),
                'new_values' => empty($newValues) ? null : json_encode($newValues),
                'ip_address' => Request::ip() ?? '127.0.0.1',
            ]);
        }
    }
}
