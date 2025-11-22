<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Checklist - {{ $checklist->job_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 15px;
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #64748b;
            font-size: 12px;
            margin: 5px 0;
        }

        .info-section {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #1e3a8a;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #1e3a8a;
            width: 120px;
            padding: 3px 10px 3px 0;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
            font-size: 15px;
            font-weight: bolder;
        }

        .category {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .category-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 0;
        }

        .category-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: white;
        }

        .category-table th {
            background: #f1f5f9;
            color: #1e3a8a;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .category-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .category-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .status-check {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        .status-yes {
            color: #22c55e;
        }

        .status-no {
            color: #ef4444;
        }

        .item-name {
            font-weight: 500;
            color: #1e293b;
            font-size: 15px;
        }

        .icone_promova {
            width: 10%;
            border-radius: 20%;
        }

        .item-editable {
            font-style: italic;
            color: #6366f1;
        }

        .observations {
            max-width: 150px;
            word-wrap: break-word;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
        }

        .quantity {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        .footer p {
            color: #1e3a8a
            font-weight: bolder
        }

        .completion-badge {
            background: #22c55e;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .empty-category {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 15px;
        }

        /* Quebras de página */
        .page-break {
            page-break-before: always;
        }

        @page {
            margin: 2cm;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-title">
            <img class="icone_promova" src="{{ asset('images/promova.jpg') }}" alt="" srcset="">
            <h1>Checklist de Produção</h1>
        </div>
        {{-- <p class="subtitle">Documento gerado automaticamente em {{ now()->format('d/m/Y H:i:s') }}</p> --}}
    </div>

    <!-- Informações do Job -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nome do Job:</div>
                <div class="info-value"><strong>{{ $checklist->job_name }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Data:</div>
                <div class="info-value">{{ $checklist->job_date }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Responsável:</div>
                <div class="info-value">{{ $checklist->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value"><span class="completion-badge">100% Completo</span></div>
            </div>
        
        </div>
    </div>

      <!-- Categorias -->
    @foreach($checklist->organized_data as $categoryName => $items)
        <div class="category">
            <div class="category-header">
                {{ $categoryName }}
            </div>
            
            @if(count($items) > 0)
                <table class="category-table">
                    <thead>
                        <tr>
                            <th style="width: 35%; font-size:15px; font-weight:bolder;">Item</th>
                            <th style="width: 10%; font-size:15px; font-weight:bolder;">Qnt</th>
                            <th style="width: 8%; font-size:15px; font-weight:bolder;">Ida</th>
                            <th style="width: 8%; font-size:15px; font-weight:bolder;">Volta</th>
                            <th style="width: 39%; font-size:15px; font-weight:bolder;">Observações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="item-name">
                                    {{ $item['name'] }}
                                    {{-- @if($item['is_editable'])
                                        <small>(Personalizado)</small>
                                    @endif --}}
                                </td>
                                <td class="quantity">
                                    {{ $item['quantity'] ?: '-' }}
                                </td>
                                <td class="status-check {{ $item['ida'] ? 'status-yes' : 'status-no' }}">
                                    {{ $item['ida'] ? '✔️' : '✗' }}
                                </td>
                                <td class="status-check {{ $item['volta'] ? 'status-yes' : 'status-no' }}">
                                    {{ $item['volta'] ? '✔️' : '✗' }}
                                </td>
                                <td class="observations">
                                    {{ $item['observations'] ?: '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{-- <div class="empty-category">
                    Nenhum item nesta categoria
                </div> --}}
            @endif
        </div>
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <p>Checklist de Produção - Sistema Promova</p>
        {{-- <p>Documento gerado automaticamente • Todos os itens foram verificados</p> --}}
    </div>
</body>

</html>
