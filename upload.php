<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $dbname = 'streamflix';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $titulo = $_POST['titulo'];
        $categoria = $_POST['categoria'];
        $data_lancamento = $_POST['data_lancamento'] ?? null;

        if ($data_lancamento) {
            $data_lancamento = DateTime::createFromFormat('Y-m-d', $data_lancamento);
            if (!$data_lancamento) {
                die("Data de lançamento inválida.");
            }
            $data_lancamento = $data_lancamento->format('Y-m-d');
        } else {
            $data_lancamento = null;
        }

        $sinopse = $_POST['sinopse'];

        // Upload da imagem
        $imagem = $_FILES['imagem'];
        $imagem_nome = 'uploads/imagens/' . basename($imagem['name']);
        if (!move_uploaded_file($imagem['tmp_name'], $imagem_nome)) {
            die("Erro ao fazer upload da imagem.");
        }

        // Upload do arquivo do filme
        $arquivo = $_FILES['arquivo'];
        $arquivo_nome = 'uploads/filmes/' . basename($arquivo['name']);
        if (!move_uploaded_file($arquivo['tmp_name'], $arquivo_nome)) {
            die("Erro ao fazer upload do arquivo do filme.");
        }
        // Upload do trailer
        $trailer = $_FILES['trailer'];
        $trailer_nome = 'uploads/trailers/' . basename($trailer['name']);
        if (!move_uploaded_file($trailer['tmp_name'], $trailer_nome)) {
        die("Erro ao fazer upload do trailer do filme.");
        }

        // Inserir no banco de dados
        $sql = "INSERT INTO filmes (titulo, imagem, arquivo, trailer, categoria, sinopse, data_lancamento) 
        VALUES (:titulo, :imagem, :arquivo, :trailer, :categoria, :sinopse, :data_lancamento)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':imagem' => $imagem_nome,
            ':arquivo' => $arquivo_nome,
            ':trailer' => $trailer_nome,
            ':categoria' => $categoria,
            ':sinopse' => $sinopse,
            ':data_lancamento' => $data_lancamento,
        ]);
        

        echo "Filme enviado com sucesso!";
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Filme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="upload.css">
    
    <style>
        .dropdown-menu a {
            color: #ffffff;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            color: #e50914;
            background-color: transparent;
        }

        .dropdown-menu {
            background-color: #1c1c1c;
        }
    </style>
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="pg-principal.php">Streamflix</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="pg-principal.php">Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ⚙
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-white" aria-labelledby="userMenu">
                            <li><a class="dropdown-item text-white" href="perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item text-white" href="logout.php">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Upload de Filme</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título do Filme</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-select" id="categoria" name="categoria" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Ação">Ação</option>
                    <option value="Aventura">Aventura</option>
                    <option value="Cinema de arte">Cinema de arte</option>
                    <option value="Chanchada">Chanchada</option>
                    <option value="Comédia">Comédia</option>
                    <option value="Comédia de ação">Comédia de ação</option>
                    <option value="Comédia de terror">Comédia de terror</option>
                    <option value="Comédia dramática">Comédia dramática</option>
                    <option value="Comédia romântica">Comédia romântica</option>
                    <option value="Dança">Dança</option>
                    <option value="Documentário">Documentário</option>
                    <option value="Docuficção">Docuficção</option>
                    <option value="Drama">Drama</option>
                    <option value="Espionagem">Espionagem</option>
                    <option value="Faroeste">Faroeste</option>
                    <option value="Fantasia">Fantasia</option>
                    <option value="Fantasia científica">Fantasia científica</option>
                    <option value="Ficção científica">Ficção científica</option>
                    <option value="Filme épico">Filme épico</option>
                    <option value="Filmes com truques">Filmes com truques</option>
                    <option value="Filmes de guerra">Filmes de guerra</option>
                    <option value="Filme policial">Filme policial</option>
                    <option value="Mistério">Mistério</option>
                    <option value="Musical">Musical</option>
                    <option value="Romance">Romance</option>
                    <option value="Terror">Terror</option>
                    <option value="Thriller">Thriller</option>
                    
                </select>
            </div>
            <div class="mb-3">
                <label for="data_lancamento" class="form-label">Data de Lançamento</label>
                <input type="date" class="form-control" id="data_lancamento" name="data_lancamento" required>
            </div>
            <div class="mb-3">
                <label for="sinopse" class="form-label">Sinopse</label>
                <textarea class="form-control" id="sinopse" name="sinopse" rows="4" placeholder="Escreva a sinopse do filme" required></textarea>
            </div>
            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem do Filme</label>
                <input type="file" class="form-control" id="imagem" name="imagem" required>
            </div>
            <div class="mb-3">
                <label for="arquivo" class="form-label">Arquivo do Filme</label>
                <input type="file" class="form-control" id="arquivo" name="arquivo" required>
            </div>
            <div class="mb-3">
                <label for="trailer" class="form-label">Trailer do Filme</label>
                <input type="file" class="form-control" id="trailer" name="trailer" required>
            </div>

            
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
