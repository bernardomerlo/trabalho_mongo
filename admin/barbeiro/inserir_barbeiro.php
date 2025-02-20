<?php

include_once '../../start/init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['name']);
    $senha = $_POST['password'];
    $confirm_senha = $_POST['confirm_password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($nome) || empty($senha) || empty($confirm_senha)) {
        $error = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirm_senha) {
        $error = "As senhas não coincidem.";
    } else {
        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

        $foto = "imgs/default_image_barbeiro.png";

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $foto_nome = $_FILES['photo']['name'];
            $foto_tmp = $_FILES['photo']['tmp_name'];
            $foto_destino_relativo = "imgs/" . basename($foto_nome);
            $foto_destino_absoluto = "../../" . $foto_destino_relativo;

            if (move_uploaded_file($foto_tmp, $foto_destino_absoluto)) {
                $foto = $foto_destino_relativo;
            } else {
                $error = "Erro ao fazer upload da foto.";
            }
        }

        if (!isset($error)) {
            try {
                
                $mongo->insert("barbeiros", [
                    "nome" => $nome,
                    "senha" => $hashed_password,
                    "foto" => $foto,
                    "admin" => $is_admin,
                    "barbearia_id" => $_SESSION["user"]->barbearia_id
                ]);

                header("Location: ../gerenciar_barbearia.php");
                exit();
            } catch (Exception $e) {
                $error = "Erro ao inserir barbeiro: " . $e->getMessage();
            }
        }
    }
}
