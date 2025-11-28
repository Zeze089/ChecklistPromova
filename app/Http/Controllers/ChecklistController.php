<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; //  Adicione esta linha

class ChecklistController extends Controller
{
    public function index()
    {

        $listas = Checklist::where('is_completed', 0)->orderBy('job_date', 'DESC')->paginate('10');

        return view('checklist.index', compact('listas'));
    }

    public function create()
    {
        return view('checklist.checklist');
    }

    /**
     * Salvar checklist completo e gerar PDF
     */
    public function saveCompleted(Request $request)
    {
        try {
            $validated = $request->validate([
                'job_name' => 'required|string|max:255',
                'job_date' => 'required|string|size:10',
                'checklist_data' => 'required|array',
                'is_completed' => 'required|boolean',
            ]);

            if (! $this->isValidDate($validated['job_date'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data deve estar no formato dd/mm/yyyy',
                ], 422);
            }

            //  Validar se está realmente completo
            /* if ($validated['is_completed'] && !$this->isChecklistComplete($validated['checklist_data'])) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Checklist deve estar 100% completo'
                 ], 422);
             }*/

            $dateForDatabase = $this->convertDateToDatabaseFormat($validated['job_date']);

            $checklist = Checklist::create([
                'user_id' => Auth::id(),
                'job_name' => $validated['job_name'],
                'job_date' => $dateForDatabase,
                'checklist_data' => $validated['checklist_data'], // Laravel faz o json_encode automaticamente via cast
                'is_completed' => $validated['is_completed'],
                'completed_at' => $validated['is_completed'] ? now() : null,
            ]);

            // Sempre gera PDF (concluído ou não)
            $pdfPath = $this->generatePDF($checklist);
            $checklist->update(['pdf_path' => $pdfPath]);

            return response()->json([
                'success' => true,
                'message' => $validated['is_completed']
                    ? 'Checklist concluído e salvo com sucesso!'
                    : 'Checklist salvo com sucesso!',
                'checklist_id' => $checklist->id,
                'pdf_url' => route('checklist.download', $checklist->id),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: '.implode(', ', $e->validator->errors()->all()),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Erro ao salvar checklist: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $checklist = Checklist::where('user_id', Auth::id())->findOrFail($id);
        // O cast 'array' no Model já converte automaticamente, não precisa de json_decode
        $checklistData = $checklist->checklist_data ?? [];

        return view('checklist.edit', compact('checklist', 'checklistData'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'job_name' => 'required|string|max:255',
            'job_date' => 'required|string|size:10',
            'checklist_data' => 'required|array',
            'is_completed' => 'required|boolean',
        ]);

        try {
            $jobDate = Carbon::createFromFormat('d/m/Y', $validated['job_date'])->format('Y-m-d');

            $checklist = Checklist::where('user_id', Auth::id())->findOrFail($id);

            $checklist->update([
                'job_name' => $validated['job_name'],
                'job_date' => $jobDate,
                'checklist_data' => $validated['checklist_data'], // Laravel faz o json_encode automaticamente via cast
                'is_completed' => $validated['is_completed'],
                'completed_at' => $validated['is_completed'] ? now() : null,
            ]);

            // Sempre gera PDF (concluído ou não)
            $pdfPath = $this->generatePDF($checklist);
            $checklist->update(['pdf_path' => $pdfPath]);

            return response()->json([
                'success' => true,
                'message' => $validated['is_completed']
                    ? 'Checklist atualizado e concluído com sucesso!'
                    : 'Checklist atualizado com sucesso!',
                'checklist_id' => $checklist->id,
                'pdf_url' => route('checklist.download', $checklist->id),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checklist não encontrado.',
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: '.implode(', ', $e->validator->errors()->all()),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar checklist: '.$e->getMessage());
            Log::error('Stack: '.$e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar checklist.',
            ], 500);
        }
    }

    /**
     * Gerar PDF do checklist
     */
    private function generatePDF($checklist)
    {
        try {
            Log::info('Iniciando geração de PDF para checklist: '.$checklist->id);

            $pdf = Pdf::loadView('checklist.pdf', compact('checklist'));
            $pdf->setPaper('A4', 'portrait');

            $fileName = 'checklist-'.$checklist->id.'-'.now()->format('Y-m-d-H-i-s').'.pdf';

            // ✅ Usar o Storage do Laravel
            $directory = 'checklists';
            $filePath = $directory.'/'.$fileName;

            // Garantir que a pasta existe no storage
            if (! Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
                Log::info('Pasta criada no storage');
            }

            // Caminho completo para salvar
            $fullPath = storage_path('app/public/'.$filePath);

            $pdf->save($fullPath);

            Log::info('PDF salvo em: '.$fullPath);
            Log::info('Arquivo existe: '.(file_exists($fullPath) ? 'SIM' : 'NÃO'));

            // ✅ Retornar caminho relativo para acesso via URL
            return 'storage/'.$filePath;

        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF: '.$e->getMessage());
            Log::error('Stack: '.$e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Validar formato da data dd/mm/yyyy
     */
    private function isValidDate($date)
    {
        if (! preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return false;
        }

        try {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);

            return $parsedDate->format('d/m/Y') === $date;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar se checklist está realmente completo
     */
    private function isChecklistComplete($checklistData)
    {
        if (! isset($checklistData['items']) || empty($checklistData['items'])) {
            return false;
        }

        $totalCheckboxes = 0;
        $checkedBoxes = 0;

        foreach ($checklistData['items'] as $item) {
            // Contar checkboxes de ida e volta
            if (isset($item['ida'])) {
                $totalCheckboxes++;
                if ($item['ida'] === true) {
                    $checkedBoxes++;
                }
            }

            if (isset($item['volta'])) {
                $totalCheckboxes++;
                if ($item['volta'] === true) {
                    $checkedBoxes++;
                }
            }
        }

        // Só considera completo se tem checkboxes E todos estão marcados
        return $totalCheckboxes > 0 && $checkedBoxes === $totalCheckboxes;
    }

    /**
     * Download do PDF (rota adicional)
     */
    public function downloadPDF($id)
    {
        try {
            $checklist = Checklist::where('user_id', Auth::id())->findOrFail($id);


            if (! $checklist->pdf_path) {
                return back()->with('error', 'PDF não encontrado');
            }

            // Extrair apenas o nome do arquivo do path salvo
            $fileName = basename($checklist->pdf_path);
            $fullPath = storage_path('app/public/checklists/'.$fileName);

            Log::info('Tentando baixar: '.$fullPath);

            if (! file_exists($fullPath)) {
                return back()->with('error', 'Arquivo não encontrado no servidor');
            }

            // Forçar download como PDF
            return response()->download($fullPath, 'checklist-'.$checklist->job_name.'.pdf', [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            Log::error('Erro download PDF: '.$e->getMessage());

            return back()->with('error', 'Erro ao baixar PDF');
        }
    }

    /**
     * Listar checklists salvos do usuário
     */
    public function history()
    {
        $checklists = Checklist::where('user_id', Auth::id())
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('checklist.history', compact('checklists'));
    }

    /**
     * Converter data de dd/mm/yyyy para yyyy-mm-dd
     */
    private function convertDateToDatabaseFormat($date)
    {
        [$day, $month, $year] = explode('/', $date);

        return "$year-$month-$day";
    }
}
