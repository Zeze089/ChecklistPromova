<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_name',
        'job_date',
        'checklist_data',
        'is_completed',
        'completed_at',
        'pdf_path'
    ];

    protected $casts = [
        'checklist_data' => 'array',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Converter data do formato dd/mm/yyyy para Carbon
    public function getFormattedJobDateAttribute()
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $this->job_date);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Organizar dados por categoria para o PDF
    public function getOrganizedDataAttribute()
    {
        $data = $this->checklist_data;
        
        // Log para debug
        Log::info('Checklist Data:', ['data' => $data]);
        
        $organized = [];

        // Verificar se há dados e se estão no formato correto
        if (!$data) {
            Log::warning('Nenhum dado encontrado no checklist');
            return $organized;
        }

        // Verificar se os dados estão no formato novo (com 'categories')
        if (isset($data['categories'])) {
            // Formato novo: dados já estão organizados por categoria
            foreach ($data['categories'] as $categoryName => $items) {
                $organized[$categoryName] = [];
                
                foreach ($items as $item) {
                    $organized[$categoryName][] = [
                        'name' => $item['name'] ?? 'Item sem nome',
                        'quantity' => $item['quantity'] ?? '',
                        'ida' => $item['ida'] ?? false,
                        'volta' => $item['volta'] ?? false,
                        'observations' => $item['observations'] ?? '',
                        'is_editable' => $item['isEditable'] ?? false
                    ];
                }
            }
        } 
        // Verificar formato antigo (com 'items')
        elseif (isset($data['items'])) {
            // Definir categorias
            $categories = [
                'camera' => 'Câmera',
                'lentes' => 'Lentes', 
                'iluminacao' => 'Iluminação',
                'som' => 'Som',
                'estabilizacao' => 'Estabilização',
                'extras' => 'Extras'
            ];

            // Inicializar todas as categorias
            foreach ($categories as $key => $title) {
                $organized[$title] = [];
            }

            // Mapeamento de índice para categoria
            $indexToCategory = [
                0 => 'camera',
                1 => 'lentes',
                2 => 'iluminacao',
                3 => 'som',
                4 => 'estabilizacao',
                5 => 'extras'
            ];

            // Processar cada item salvo
            foreach ($data['items'] as $itemKey => $itemData) {
                $parts = explode('-', $itemKey);
                
                if (count($parts) < 2) {
                    continue;
                }

                $tableIndex = (int) $parts[0];
                $categoryKey = $indexToCategory[$tableIndex] ?? 'extras';
                $categoryTitle = $categories[$categoryKey];

                $organized[$categoryTitle][] = [
                    'name' => $itemData['name'] ?? 'Item sem nome',
                    'quantity' => $itemData['quantity'] ?? '',
                    'ida' => $itemData['ida'] ?? false,
                    'volta' => $itemData['volta'] ?? false,
                    'observations' => $itemData['observations'] ?? '',
                    'is_editable' => $itemData['isEditable'] ?? false
                ];
            }
        }

        // Log do resultado organizado
        Log::info('Dados organizados:', ['organized' => $organized]);

        return $organized;
    }

    public function getPdfUrlAttribute()
    {
        if ($this->pdf_path) {
            return route('checklist.download', $this->id);
        }
        return null;
    }
}