<?php

include_once '../../start/init.php';

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id_barbeiro = intval($_POST['id']);
    $nome = trim($_POST['name']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    try {
        
        $barbeiro = $mongo->selectOne("barbeiros", ["id" => $id_barbeiro]);
        
        if (!$barbeiro) {
            header("Location: gerenciar_barbearia.php");
            exit();
        }

        $foto = $barbeiro->foto;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $foto_nome = $_FILES['photo']['name'];
            $foto_tmp = $_FILES['photo']['tmp_name'];
            $foto_destino_relativo = "imgs/" . basename($foto_nome);
            $foto_destino_absoluto = "../" . $foto_destino_relativo;

            if (move_uploaded_file($foto_tmp, $foto_destino_absoluto)) {
                $foto = $foto_destino_relativo;
            } else {
                $_SESSION['error'] = "Erro ao fazer upload da foto.";
                header("Location: ../forms/formulario_editar_barbeiro.php?id=$id_barbeiro");
                exit();
            }
        }

        
        $mongo->update("barbeiros", ["id" => $id_barbeiro], [
            "nome" => $nome,
            "foto" => $foto,
            "admin" => $is_admin
        ]);

        $_SESSION['success'] = "Barbeiro atualizado com sucesso!";
        header("Location: ../gerenciar_barbearia.php");
        exit();
    } catch (Exception $e) {
        error_log("Erro ao editar barbeiro com ID $id_barbeiro: " . $e->getMessage());
        $_SESSION['error'] = "Erro ao editar barbeiro.";
        header("Location: ../forms/formulario_editar_barbeiro.php?id=$id_barbeiro");
        exit();
    }
} else {
    header("Location: ../gerenciar_barbearia.php");
    exit();
}
