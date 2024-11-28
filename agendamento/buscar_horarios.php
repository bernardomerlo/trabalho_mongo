<?php

include_once '../start/init.php';

if (isset($_GET["id_barbeiro"]) && isset($_GET["data"])) {
    $id_barbeiro = $_GET["id_barbeiro"];
    $data = $_GET["data"];

    $ocupados = $mongo->select("cortes", [
        "barbeiro_id" => $id_barbeiro,
        "data_corte" => $data
    ], ["projection" => ["horario" => 1]]);

    $ocupadosHorarios = array_map(fn($c) => $c['horario'], $ocupados);

    $todosHorarios = $mongo->select("horarios", [
        "horario" => ['$gte' => "09:00", '$lte' => "19:00"]
    ]);

    $horarios = array_filter($todosHorarios, fn($h) => !in_array($h['horario'], $ocupadosHorarios));

    $horariosDisponiveis = [];
    foreach ($horarios as $horario) {
        // Certificar-se de que o campo 'horario' está presente
        if (isset($horario->horario)) {
            $horariosDisponiveis[] = $horario->horario;
        }
    }

    echo json_encode($horariosDisponiveis);
}
