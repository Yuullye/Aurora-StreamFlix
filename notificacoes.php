<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'streamflix');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca os pedidos de amizade para o usuário logado
$sql = "SELECT pedidos.id AS pedido_id, 
               usuarios.id AS usuario_id, 
               usuarios.username, 
               usuarios.foto_perfil 
        FROM pedidos_amizade AS pedidos
        JOIN usuarios ON pedidos.solicitante_id = usuarios.id
        WHERE pedidos.destinatario_id = ? AND pedidos.status = 'pendente'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// Aceitar ou recusar um pedido de amizade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $acao = $_POST['acao'];

    if ($acao === 'aceitar') {
        // Atualiza o status do pedido para 'aceito' e adiciona à lista de amigos
        $sql_aceitar = "UPDATE pedidos_amizade SET status = 'aceito' WHERE id = ?";
        $stmt_aceitar = $conn->prepare($sql_aceitar);
        $stmt_aceitar->bind_param("i", $pedido_id);
        $stmt_aceitar->execute();

        // Insere a relação de amizade na tabela de amigos
        $sql_adicionar_amigo = "INSERT INTO amigos (usuario_id, amigo_id) 
                                SELECT destinatario_id, solicitante_id FROM pedidos_amizade WHERE id = ?";
        $stmt_amigos = $conn->prepare($sql_adicionar_amigo);
        $stmt_amigos->bind_param("i", $pedido_id);
        $stmt_amigos->execute();
    } elseif ($acao === 'recusar') {
        // Atualiza o status do pedido para 'recusado'
        $sql_recusar = "UPDATE pedidos_amizade SET status = 'recusado' WHERE id = ?";
        $stmt_recusar = $conn->prepare($sql_recusar);
        $stmt_recusar->bind_param("i", $pedido_id);
        $stmt_recusar->execute();
    }

    // Redireciona para evitar reenvio do formulário
    header("Location: notificacoes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Orbitron', sans-serif;
        }

        .notification-card {
            background: #1c1c1c;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .notification-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .notification-card .username {
            font-weight: bold;
            color: #e50914;
        }

        .notification-card .actions {
            margin-left: auto;
        }

        .notification-card button {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="pg-principal.php">Aurora</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="pg-principal.php">Home</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h1>Notificações</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification-card">
                    <img src="<?php echo $row['foto_perfil'] ?: 'default-profile.png'; ?>" alt="Foto de perfil">
                    <div>
                        <p class="username"><?php echo htmlspecialchars($row['username']); ?></p>
                        <small>Enviou um pedido de amizade</small>
                    </div>
                    <div class="actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                            <button type="submit" name="acao" value="aceitar" class="btn btn-success btn-sm">Aceitar</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $row['pedido_id']; ?>">
                            <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Você não tem novas notificações.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
