<?php
session_start();
$is_logged_in = isset($_SESSION['usuario']); // Verifica se o usuário está logado
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

if (!$is_logged_in) {
    header("Location: login.php");
    exit;
}

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'streamflix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter o ID do usuário logado
    $usuario_id = $_SESSION['usuario_id'];

    // Determinar se é o próprio perfil ou de outro usuário
    if (isset($_GET['id'])) {
        $perfil_id = $_GET['id'];
    } else {
        $perfil_id = $usuario_id; // Caso não seja especificado, é o perfil do próprio usuário
    }

    // Obter os dados do perfil
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $perfil_id]);
    $perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$perfil_usuario) {
        echo "Usuário não encontrado.";
        exit;
    }

    // Verificar status de amizade (apenas para outros perfis)
    $amizade = null;
    if ($perfil_id != $usuario_id) {
        $sql = "SELECT * FROM pedidos_amizade 
                WHERE (solicitante_id = :usuario_id AND destinatario_id = :perfil_id) 
                   OR (solicitante_id = :perfil_id AND destinatario_id = :usuario_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id, 'perfil_id' => $perfil_id]);
        $amizade = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Processar ações do formulário
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Enviar pedido de amizade
        if (isset($_POST['enviar_pedido']) && !$amizade) {
            $sql = "INSERT INTO pedidos_amizade (solicitante_id, destinatario_id, status) VALUES (:solicitante_id, :destinatario_id, 'pendente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['solicitante_id' => $usuario_id, 'destinatario_id' => $perfil_id]);
            echo "<p class='text-info'>Pedido de amizade enviado com sucesso.</p>";
        }

        // Aceitar pedido de amizade
        // Aceitar pedido de amizade
if (isset($_POST['aceitar_pedido']) && $amizade && $amizade['status'] == 'pendente') {
    $sql = "UPDATE pedidos_amizade SET status = 'aceito' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $amizade['id']]);

    // Inserir a amizade nos dois sentidos para garantir que ambos vejam no chat
    $sql_amigos = "INSERT INTO amigos (usuario_id, amigo_id) VALUES (:usuario_id, :amigo_id), (:amigo_id, :usuario_id)";
    $stmt_amigos = $pdo->prepare($sql_amigos);
    $stmt_amigos->execute([
        'usuario_id' => $usuario_id, // Usuário logado (quem aceitou o pedido)
        'amigo_id'   => $perfil_id   // Quem enviou o pedido
    ]);
}


        // Recusar pedido de amizade
        if (isset($_POST['recusar_pedido']) && $amizade && $amizade['status'] == 'pendente') {
            $sql = "UPDATE pedidos_amizade SET status = 'recusado' WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $amizade['id']]);
        }

        // Atualizar foto de perfil ou senha (apenas no próprio perfil)
        if ($perfil_id == $usuario_id) {
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $diretorio_fotos = 'uploads/fotos_perfil/';
                if (!is_dir($diretorio_fotos)) {
                    mkdir($diretorio_fotos, 0777, true);
                }
                $nome_foto = basename($_FILES['foto_perfil']['name']);
                $caminho_foto = $diretorio_fotos . $nome_foto;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_foto)) {
                    $sql = "UPDATE usuarios SET foto_perfil = :foto WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['foto' => $caminho_foto, 'id' => $usuario_id]);
                    $perfil_usuario['foto_perfil'] = $caminho_foto;
                } else {
                    echo "Erro ao salvar a foto de perfil.";
                }
            }

            if (!empty($_POST['nova_senha'])) {
                $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET password = :senha WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['senha' => $nova_senha, 'id' => $usuario_id]);
                echo "Senha atualizada com sucesso.";
            }
        }
    }
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Aurora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="pg-principal.php">Home</a>
                    </li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item user-menu" style="position: relative;">
                            <button 
                                class="config-button"
                                title="Configurações"
                            >
                                ⚙
                            </button>
                            <ul class="config-menu">
                                <li><a href="perfil.php" class="config-link">Perfil</a></li>
                                <li><a href="historico.php" class="config-link">Histórico</a></li>
                                <li><a href="favoritos.php" class="config-link">Favoritos</a></li>
                                <li><a href="logout.php" class="config-link">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar sessão</a> 
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const configButton = document.querySelector(".config-button");
        const configMenu = document.querySelector(".config-menu");

        configButton.addEventListener("click", () => {
            configMenu.style.display = configMenu.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", (event) => {
            if (!configButton.contains(event.target) && !configMenu.contains(event.target)) {
                configMenu.style.display = "none";
            }
        });
    });
</script>

    
    <div class="profile-container">
        <div class="profile-card">
            <img src="<?php echo $perfil_usuario['foto_perfil'] ? $perfil_usuario['foto_perfil'] : 'default-profile.png'; ?>" alt="Foto de Perfil" class="profile-image">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($perfil_usuario['username']); ?></h2>
                <p><?php echo htmlspecialchars($perfil_usuario['email']); ?></p>
            </div>
            <?php if ($perfil_id == $usuario_id): ?>
                <button class="edit-button" onclick="toggleEdit()">Editar</button>
                <div class="form-container" id="editForm">
                    <form method="POST" enctype="multipart/form-data">
                        <label>Atualizar Foto de Perfil:</label>
                        <input type="file" name="foto_perfil" accept="image/*">
                        <label>Alterar Senha:</label>
                        <input type="password" name="nova_senha" placeholder="Nova Senha">
                        <button type="submit">Salvar Alterações</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="text-center mt-3">
    <?php if (!$amizade): ?>
        <form method="POST">
            <button type="submit" name="enviar_pedido" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Adicionar Amizade
            </button>
        </form>
    <?php elseif ($amizade['status'] == 'pendente' && $amizade['destinatario_id'] == $usuario_id): ?>
        <form method="POST" style="display: inline;">
            <button type="submit" name="aceitar_pedido" class="btn btn-success">Aceitar</button>
        </form>
        <form method="POST" style="display: inline;">
            <button type="submit" name="recusar_pedido" class="btn btn-danger">Recusar</button>
        </form>
    <?php elseif ($amizade['status'] == 'pendente'): ?>
        <p class="text-warning">Pedido de amizade pendente.</p>
    <?php elseif ($amizade['status'] == 'aceito'): ?>
        <p class="text-success">Vocês são amigos!</p>
        <!-- Corrigido o link do botão "Conversar" -->
        <a href="chat.php?amigo_id=<?php echo $perfil_id; ?>" class="btn btn-secondary">
            <i class="bi bi-chat-dots"></i> Conversar
        </a>
    <?php elseif ($amizade['status'] == 'recusado'): ?>
        <p class="text-danger">Pedido de amizade recusado.</p>
    <?php endif; ?>
</div>

            <?php endif; ?>
        </div>
    </div>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownCard');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function toggleEdit() {
            const editForm = document.getElementById('editForm');
            editForm.style.display = editForm.style.display === 'block' ? 'none' : 'block';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
