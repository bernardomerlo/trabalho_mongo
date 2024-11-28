<?php
header('Content-Type: text/html; charset=utf-8');

include_once '../start/init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barbeiro = $_POST["id_barbeiro"];
    $nome_cliente = $_POST["nome_cliente"];
    $telefone_cliente = $_POST["telefone_cliente"];
    $data = $_POST["data"];
    $horario = $_POST["horarios"];
    $cliente_ip = $_SERVER["REMOTE_ADDR"];
    $tipo_corte = $_POST["tipo_corte"];

    try {
        $mongo->insert("cortes", [
            "nome_cliente" => $nome_cliente,
            "telefone_cliente" => $telefone_cliente,
            "data_corte" => $data,
            "barbeiro_id" => $id_barbeiro,
            "cliente" => $cliente_ip,
            "horario" => $horario,
            "tipo_corte" => $tipo_corte
        ]);

        $id = $mongo->selectOne("cortes", ["cliente" => $cliente_ip]);

        header("Location: visualiza_agendado.php?id=" . $id->_id);
        exit();
    } catch (Exception $e) {
        echo "Erro ao agendar corte: " . $e->getMessage();
    }
}
