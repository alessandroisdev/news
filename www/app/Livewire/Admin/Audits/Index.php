<?php

namespace App\Livewire\Admin\Audits;

use App\Models\SystemAudit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $searchUser = '';
    public $actionFilter = '';

    public function updatingSearchUser()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function exportCsv()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $query = SystemAudit::with('user')->latest();
        
        if ($this->searchUser) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }
        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        $audits = $query->get();

        $csvData = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvData .= "Audit ID;Data_Hora;Usuario;Email_Usuario;Acao_Executada;Entidade_Alvo;ID_Entidade;IP_Origem\n";
        
        foreach($audits as $audit) {
            $userName = $audit->user->name ?? 'Sistema/Usuário Deletado';
            $userEmail = $audit->user->email ?? 'N/A';
            $date = $audit->created_at->format('d/m/Y H:i:s');
            $type = class_basename($audit->auditable_type);
            
            $csvData .= "{$audit->id};{$date};\"{$userName}\";\"{$userEmail}\";{$audit->action};{$type};{$audit->auditable_id};{$audit->ip_address}\n";
        }

        return response()->streamDownload(function() use ($csvData) {
            echo $csvData;
        }, 'relatorio_de_auditoria_' . date('Y-m-d_H-i') . '.csv');
    }

    public function render()
    {
        if (auth()->user()->role !== \App\Enums\UserRoleEnum::ADMIN->value) {
            abort(403);
        }

        $query = SystemAudit::with('user')->latest();

        if ($this->searchUser) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }

        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        return view('livewire.admin.audits.index', [
            'logs' => $query->paginate(30)
        ])->layout('layouts.admin');
    }
}
