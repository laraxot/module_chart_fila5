<?php

declare(strict_types=1);

return [
    'fields' => [
<<<<<<< HEAD
       'id' => ['label' => 'ID'],
=======
        'id' => ['label' => 'ID'],
>>>>>>> laraxot/dev
        'type' => ['label' => 'Tipo', 'placeholder' => 'Seleziona tipo', 'help' => 'Tipo di grafico da visualizzare', 'helper_text' => 'type', 'description' => 'type'],
        'group_by' => ['label' => 'Raggruppa per', 'placeholder' => 'Nessun raggruppamento', 'help' => 'Campo per il raggruppamento dei dati', 'helper_text' => 'group_by', 'description' => 'group_by'],
        'sort_by' => ['label' => 'Ordina per', 'placeholder' => 'Nessun ordinamento', 'help' => 'Campo per l\'ordinamento dei dati', 'helper_text' => 'sort_by', 'description' => 'sort_by'],
        'width' => ['label' => 'Larghezza', 'placeholder' => 'Es: 100%', 'help' => 'Larghezza del grafico', 'helper_text' => 'width', 'description' => 'width'],
        'height' => ['label' => 'Altezza', 'placeholder' => 'Es: 400px', 'help' => 'Altezza del grafico', 'helper_text' => 'height', 'description' => 'height'],
        'font_family' => ['label' => 'Famiglia font', 'placeholder' => 'Seleziona font', 'helper_text' => 'font_family', 'description' => 'font_family'],
        'font_style' => ['label' => 'Stile font', 'placeholder' => 'Seleziona stile', 'helper_text' => 'font_style', 'description' => 'font_style'],
        'font_size' => ['label' => 'Dimensione font', 'placeholder' => 'Es: 12', 'helper_text' => 'font_size', 'description' => 'font_size'],
        'show_box' => ['label' => 'Mostra box', 'placeholder' => 'show_box', 'helper_text' => 'show_box', 'description' => 'show_box'],
        'list_color' => ['label' => 'Colore lista', 'placeholder' => 'list_color', 'helper_text' => 'list_color', 'description' => 'list_color'],
        'transparency' => ['label' => 'Trasparenza', 'placeholder' => 'transparency', 'helper_text' => 'transparency', 'description' => 'transparency'],
    ],
    'options' => [
        'type' => ['pie1' => 'Torta', 'pieAvg' => 'Torta con Media', 'horizbar1' => 'Barre Orizzontali', 'horizbar2' => 'Barre Orizzontali Accumulata', 'bar2' => 'Barre Verticali', 'bar3' => 'Barre Verticali Accumulata', 'line1' => 'Linea', 'lineSubQuestion' => 'Linea (Subquestion)'],
        'group_by' => ['date:o-W' => 'Settimanale', 'date:Y-M' => 'Mensile', 'date:Y-M-d' => 'Giornaliero', 'field:Q41' => 'Campo Q41'],
        'font_family' => [10 => 'Courier', 'Verdana', 'Times New Roman', 14 => 'Comic Sans', 'Arial', 'Georgia', 'Trebuchet MS'],
        'font_style' => [9001 => 'Normale', 'Grassetto', 'Corsivo', 'Grassetto Corsivo'],
    ],
    'navigation' => ['label' => 'Grafici', 'group' => 'Analisi', 'icon' => 'heroicon-o-chart-bar', 'sort' => 20],
];
