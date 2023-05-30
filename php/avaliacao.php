<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $especialidade = $_POST["especialidade"];
    $profissional = $_POST["profissional"];
    $atendimento = $_POST["atendimento"];
    $pontualidade = $_POST["pontualidade"];
    $atendimento_recepcao = $_POST["atendimento_recepcao"];
    $infraestrutura = $_POST["infraestrutura"];
    $nota_media = ($atendimento + $pontualidade + $atendimento_recepcao + $infraestrutura) / 4;
    $critica_sugestao = $_POST["critica_sugestao"];

    $query = "INSERT INTO avaliacoes (especialidade, profissional, atendimento, pontualidade, atendimento_recepcao, infraestrutura, nota_media, critica_sugestao)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$especialidade, $profissional, $atendimento, $pontualidade, $atendimento_recepcao, $infraestrutura, $nota_media, $critica_sugestao]);

    // Exibir modal ou mensagem de sucesso
    echo "<script>alert('Avaliação enviada com sucesso!');</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de Atendimento</title>
    
</head>

<body>
    <h1>Avaliação de Atendimento</h1>

    <form id="avaliacao-form" action="avaliacao.php" method="POST">
        <label for="especialidade">Especialidade:</label>
        <select id="especialidade" name="especialidade" required>
            <!-- Carregar as especialidades disponíveis do banco de dados -->
            <?php
            $query = "SELECT * FROM especialidades";
            $result = $conn->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
            }
            ?>
        </select>

        <label for="profissional">Profissional:</label>
        <select id="profissional" name="profissional" required>
            <!-- Carregar os profissionais de saúde da especialidade selecionada -->
            <?php
            $especialidade = $_POST["especialidade"];
            $query = "SELECT * FROM profissionais WHERE especialidade = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$especialidade]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
            }
            ?>
        </select>

        <label for="atendimento">Atendimento do profissional (1-10):</label>
        <input type="number" id="atendimento" name="atendimento" min="1" max="10" required>

        <label for="pontualidade">Pontualidade (1-10):</label>
        <input type="number" id="pontualidade" name="pontualidade" min="1" max="10" required>

        <label for="atendimento_recepcao">Atendimento na recepção (1-10):</label>
        <input type="number" id="atendimento_recepcao" name="atendimento_recepcao" min="1" max="10" required>

        <label for="infraestrutura">Infraestrutura do consultório (1-10):</label>
        <input type="number" id="infraestrutura" name="infraestrutura" min="1" max="10" required>

        <?php
        $media = ($atendimento + $pontualidade + $atendimento_recepcao + $infraestrutura) / 4;
        if ($media <= 5) {
            echo "<label for='critica_sugestao'>Crítica/Sugestão:</label>";
            echo "<textarea id='critica_sugestao' name='critica_sugestao' required></textarea>";
        } elseif ($media < 7) {
            echo "<textarea id='critica_sugestao' name='critica_sugestao' style='display: none;'></textarea>";
        } else {
            echo "<label for='critica_sugestao'>Elogio:</label>";
            echo "<textarea id='critica_sugestao' name='critica_sugestao' required></textarea>";
        }
        ?>

        <button type="submit">Enviar Avaliação</button>
    </form>

    <script src="js/script.js"></script>
</body>

</html>
