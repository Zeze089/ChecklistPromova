<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        $organized = [];

        if (!isset($data['items'])) {
            return $organized;
        }

        $categories = [
            'camera' => 'Câmera',
            'lentes' => 'Lentes', 
            'iluminacao' => 'Iluminação',
            'som' => 'Som',
            'estabilizacao' => 'Estabilização',
            'extras' => 'Extras'
        ];

        foreach ($categories as $key => $title) {
            $organized[$title] = [];
        }

        // Processar cada item salvo
        foreach ($data['items'] as $itemKey => $itemData) {
            // Determinar categoria baseada no tableIndex
            $tableIndex = (int) explode('-', $itemKey)[0];
            
            $categoryKey = array_keys($categories)[$tableIndex] ?? 'extras';
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