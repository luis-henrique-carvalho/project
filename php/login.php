<?php
require_once "config.php";

// Verificar se o formulário de login foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $cpf = $_POST["cpf"];
    $nome = $_POST["nome"];

    try {
        $query = "SELECT * FROM pacientes WHERE cpf = :cpf";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            // Paciente não está cadastrado, criar novo cadastro
            $query = "INSERT INTO pacientes (cpf, nome) VALUES (:cpf, :nome)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
        }

        // Registrar os dados do usuário no banco de dados
        $query = "INSERT INTO usuarios (cpf, nome) VALUES (:cpf, :nome)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        // Redirecionar para a página de avaliação
        header("Location: php/avaliacao.php");
        exit();
    } catch (PDOException $e) {
        // Tratar exceção (opcional)
        // Por exemplo, exibir uma mensagem de erro específica ou registrar o erro em um arquivo de log

        // Redirecionar para a página de erro
        header("Location: error.php");
        exit();
    }
}
?>
