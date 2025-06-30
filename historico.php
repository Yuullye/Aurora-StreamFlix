<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'streamflix';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar histórico do usuário
    $sql = "SELECT f.id AS filme_id, f.titulo, f.imagem, f.arquivo, f.categoria, h.assistido_em
            FROM historico h
            JOIN filmes f ON h.filme_id = f.id
            WHERE h.usuario_id = :usuario_id
            ORDER BY h.assistido_em DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario_id' => $usuario_id]);
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Filmes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #141414;
            color: #ffffff;
            font-family: 'Orbitron', sans-serif;
        }

        .navbar {
            background-color: #000;
        }

        .navbar-brand {
            font-weight: bold;
            color: #e50914 !important;
        }

        .nav-link {
            color: #bdbdbd !important;
        }

        .nav-link:hover {
            color: #e50914 !important;
        }

        .container h1 {
            color: #e50914;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .card {
            background-color: #1c1c1c;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-size: 1.2rem;
            color: #ffffff;
        }

        .card-text {
            font-size: 0.9rem;
            color: #cccccc;
        }

        .btn-primary {
            background-color: #e50914;
            border: none;
        }

        .btn-primary:hover {
            background-color: #f61c24;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
    <h1>Histórico de Filmes</h1>
    <?php if (count($historico) > 0): ?>
        <div class="row">
            <?php foreach ($historico as $item): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                            <p class="card-text">Categoria: <?php echo htmlspecialchars($item['categoria']); ?></p>
                            <p class="card-text">Assistido em: <?php echo date('d/m/Y H:i', strtotime($item['assistido_em'])); ?></p>
                            <a href="assistir.php?id=<?php echo $item['filme_id']; ?>" class="btn btn-primary">Assistir Novamente</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Você ainda não assistiu a nenhum filme.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
