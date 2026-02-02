<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => [
            'label' => 'ID',
        ],
        'type' => [
            'label' => 'Tipo',
            'placeholder' => 'Seleziona tipo',
            'help' => 'Tipo di grafico da visualizzare',
        ],
        'group_by' => [
            'label' => 'Raggruppa per',
            'placeholder' => 'Nessun raggruppamento',
            'help' => 'Campo per il raggruppamento dei dati',
        ],
        'sort_by' => [
            'label' => 'Ordina per',
            'placeholder' => 'Nessun ordinamento',
            'help' => 'Campo per l\'ordinamento dei dati',
        ],
        'width' => [
            'label' => 'Larghezza',
            'placeholder' => 'Es: 100%',
            'help' => 'Larghezza del grafico',
        ],
        'height' => [
            'label' => 'Altezza',
            'placeholder' => 'Es: 400px',
            'help' => 'Altezza del grafico',
        ],
        'font_family' => [
            'label' => 'Famiglia font',
            'placeholder' => 'Seleziona font',
        ],
        'font_style' => [
            'label' => 'Stile font',
            'placeholder' => 'Seleziona stile',
        ],
        'font_size' => [
            'label' => 'Dimensione font',
            'placeholder' => 'Es: 12',
        ],
        'show_box' => [
            'label' => 'Mostra box',
        ],
        'list_color' => [
            'label' => 'Colore lista',
        ],
        'transparency' => [
            'label' => 'Trasparenza',
        ],
    ],
    'options' => [
        'type' => [
            'pie1' => 'Torta',
            'pieAvg' => 'Torta con Media',
            'horizbar1' => 'Barre Orizzontali',
            'horizbar2' => 'Barre Orizzontali Accumulata',
            'bar2' => 'Barre Verticali',
            'bar3' => 'Barre Verticali Accumulata',
            'line1' => 'Linea',
            'lineSubQuestion' => 'Linea (Subquestion)',
        ],
        'group_by' => [
            'date:o-W' => 'Settimanale',
            'date:Y-M' => 'Mensile',
            'date:Y-M-d' => 'Giornaliero',
            'field:Q41' => 'Campo Q41',
        ],
        'font_family' => [
            10 => 'Courier',
            11 => 'Verdana',
            12 => 'Times New Roman',
            14 => 'Comic Sans',
            15 => 'Arial',
            16 => 'Georgia',
            17 => 'Trebuchet MS',
        ],
        'font_style' => [
            9001 => 'Normale',
            9002 => 'Grassetto',
            9003 => 'Corsivo',
            9004 => 'Grassetto Corsivo',
        ],
    ],
    'navigation' => [
        'label' => 'Grafici',
        'group' => 'Analisi',
        'icon' => 'heroicon-o-chart-bar',
        'sort' => 20,
    ],
];
