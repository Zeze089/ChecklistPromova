<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function historico(Request $request)
    {
        $pesquisar = $request->input('pesquisar');

        $historicos = Checklist::query()
            ->orderBy('id', 'DESC')
            ->when(!auth()->user()->is_admin, function ($query) {
                // Se não for admin, mostra apenas seus próprios checklists
                $query->where('user_id', auth()->id());
            })
            ->when($pesquisar, function ($query, $pesquisar) {
                $query->where('job_name', 'like', "%{$pesquisar}%");
            })
            ->paginate(15);

        return view('historico.index', compact('historicos'));
    }

    public function show(Checklist $checklist)
    {
        // Se não for admin, só pode ver seu próprio checklist
        if (!auth()->user()->is_admin && $checklist->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para ver este checklist.');
        }
        
        return view('historico.show', compact('checklist'));
    }
}