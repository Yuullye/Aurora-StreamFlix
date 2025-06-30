<?php
session_start();
$is_logged_in = isset($_SESSION['usuario']);
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'streamflix');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializa as variáveis de pesquisa
$search_query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// Query para buscar categorias e seus filmes incluindo o gênero
if (!empty($search_query)) {
    $sql = "SELECT * FROM filmes 
            WHERE titulo LIKE '%$search_query%' 
            OR descricao LIKE '%$search_query%' 
            OR categoria LIKE '%$search_query%' 
            ORDER BY categoria, titulo";
} else {
    $sql = "SELECT * FROM filmes ORDER BY categoria, titulo";
}

$result = $conn->query($sql);

// Organizar filmes por categoria
$filmes_por_categoria = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoria = $row['categoria'];
        if (!isset($filmes_por_categoria[$categoria])) {
            $filmes_por_categoria[$categoria] = [];
        }
        $filmes_por_categoria[$categoria][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filmes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="./lista_filmes.css">
    


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
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="pg-principal.php">Home</a>
                    </li>
                   
                </ul>
                <form class="d-flex" method="GET" action="">
                    <input 
                        class="form-control me-2 search-input" 
                        type="search" 
                        name="q" 
                        value="<?php echo htmlspecialchars($search_query); ?>" 
                        placeholder="Pesquise por filmes..." 
                        aria-label="Search">
                    <button class="btn btn-danger search-button" type="submit">Pesquisar</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h1 class="text-center mb-4">Lista de Filmes</h1>
    <?php if (!empty($filmes_por_categoria)): ?>
        <?php foreach ($filmes_por_categoria as $categoria => $filmes): ?>
            <h2 class="category-title"><?php echo htmlspecialchars($categoria); ?></h2>
            <div class="scroll-container">
    <button class="scroll-button prev">
        <img src="./imagens/angle-left.png" alt="Seta para a esquerda">
    </button>
    <div class="scrolling-wrapper">
    <?php foreach ($filmes as $filme): ?>
                        <div class="movie-card">
                            <img src="<?php echo $filme['imagem']; ?>" alt="Imagem do Filme">
                            <div class="p-3">
                                <h2 class="movie-title"><?php echo htmlspecialchars($filme['titulo']); ?></h2>
                                <p class="movie-description"><?php echo substr(htmlspecialchars($filme['descricao']), 0, 100); ?>...</p>
                                <a href="detalhes.php?id=<?php echo $filme['id']; ?>" class="btn-watch">Assistir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
    </div>
    <button class="scroll-button next">
        <img src="./imagens/angle-right.png" alt="Seta para a direita">
    </button>
</div>

        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">Não há filmes disponíveis no momento.</p>
    <?php endif; ?>
</div>
<script>
    document.querySelectorAll('.scroll-container').forEach(container => {
        const wrapper = container.querySelector('.scrolling-wrapper');
        const prevButton = container.querySelector('.scroll-button.prev');
        const nextButton = container.querySelector('.scroll-button.next');

        // Scroll para a esquerda
        prevButton.addEventListener('click', () => {
            wrapper.scrollBy({
                left: -300, // Ajuste o valor conforme necessário
                behavior: 'smooth'
            });
        });

        // Scroll para a direita
        nextButton.addEventListener('click', () => {
            wrapper.scrollBy({
                left: 300, // Ajuste o valor conforme necessário
                behavior: 'smooth'
            });
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
