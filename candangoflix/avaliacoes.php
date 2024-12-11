<?php
ini_set("display_errors", 1);
ini_set("startup_display_errors", 1);
error_reporting(E_ALL);

$servername = "localhost"; 
$username = "root";        
$password = "ifsp";           
$dbname = "candangofilmes";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inserir avaliação
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nome = $_POST["nome"];
    $era_bom = $_POST["era_bom"];
    $comentario = $_POST["comentario"] ?? null;
    
    $sql = "INSERT INTO avaliacoes (nome, era_bom, comentario) VALUES ('$nome', '$era_bom', '$comentario')";
    if ($conn->query($sql) === TRUE) {
        header("Location: avaliacoes.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Deletar avaliação
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM avaliacoes WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: avaliacoes.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Editar avaliação
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $era_bom = $_POST["era_bom"];
    $comentario = $_POST["comentario"];

    $sql = "UPDATE avaliacoes SET nome='$nome', era_bom='$era_bom', comentario='$comentario' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: avaliacoes.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Ler avaliações
$sql = "SELECT * FROM avaliacoes";
$result = $conn->query($sql);
$avaliacoes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $avaliacoes[] = $row;
    }
}

// Verificar se há uma avaliação para editar
$avaliacaoParaEditar = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM avaliacoes WHERE id='$id'";
    $result = $conn->query($sql);
    $avaliacaoParaEditar = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações de Filmes</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Avaliações de Filmes</h1>
    
    <form method="POST">
        <input type="hidden" name="action" value="insert">
        <label>Nome do Filme:</label>
        <input type="text" name="nome" required>
        
        <label>O filme era bom?</label>
        <select name="era_bom" required>
            <option value="sim">Sim</option>
            <option value="não">Não</option>
        </select>
        
        <label>Comentário (opcional):</label>
        <textarea name="comentario"></textarea>
        
        <button type="submit">Enviar Avaliação</button>
    </form>

    <h2>Avaliações</h2>
    <ul>
        <?php foreach ($avaliacoes as $avaliacao): ?>
            <li>
                <strong><?php echo htmlspecialchars($avaliacao['nome']); ?></strong> - 
                <?php echo htmlspecialchars($avaliacao['era_bom']); ?> 
                <p><?php echo htmlspecialchars($avaliacao['comentario']); ?></p>
                <a href="avaliacoes.php?edit=<?php echo $avaliacao['id']; ?>">Editar</a>
                <a href="avaliacoes.php?delete=<?php echo $avaliacao['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</a>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <?php if ($avaliacaoParaEditar): ?>
        <h2>Editar Avaliação</h2>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo $avaliacaoParaEditar['id']; ?>">
            <label>Nome do Filme:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($avaliacaoParaEditar['nome']); ?>" required>
            
            <label>O filme era bom?</label>
            <select name="era_bom" required>
                <option value="sim" <?php echo $avaliacaoParaEditar['era_bom'] == 'sim' ? 'selected' : ''; ?>>Sim</option>
                <option value="não" <?php echo $avaliacaoParaEditar['era_bom'] == 'não' ? 'selected' : ''; ?>>Não</option>
            </select>
            
            <label>Comentário (opcional):</label>
            <textarea name="comentario"><?php echo htmlspecialchars($avaliacaoParaEditar['comentario']); ?></textarea>
            
            <button type="submit">Atualizar Avaliação</button>
        </form>
    <?php endif; ?>
    
</body>
</html>
