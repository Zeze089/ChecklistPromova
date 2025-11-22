<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Se for admin, mostra estatísticas de todos os usuários
        // Se não, mostra apenas as estatísticas do usuário logado
        if ($user->is_admin) {
            // Total de checklists (todos os usuários)
            $totalChecklists = Checklist::count();
            
            // Checklists criados este mês (todos os usuários)
            $checklistsThisMonth = Checklist::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
        } else {
            // Total de checklists do usuário
            $totalChecklists = Checklist::where('user_id', $user->id)->count();
            
            // Checklists criados este mês (usuário específico)
            $checklistsThisMonth = Checklist::where('user_id', $user->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
        }
        
        // Total de usuários ativos (últimos 30 dias)
        $activeUsers = User::where('updated_at', '>=', Carbon::now()->subDays(30))->count();
        
        return view('dashboard', compact(
            'totalChecklists',
            'checklistsThisMonth',
            'activeUsers'
        ));
    }
}