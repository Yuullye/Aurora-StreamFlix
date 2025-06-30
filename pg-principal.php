<?php
session_start();
$is_logged_in = isset($_SESSION['usuario']);
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'streamflix');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Termo de pesquisa
$search_query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// Query para buscar filmes
if (!empty($search_query)) {
    $sql = "SELECT * FROM filmes WHERE titulo LIKE '%$search_query%' OR descricao LIKE '%$search_query%' ORDER BY criado_em DESC";
} else {
    $sql = "SELECT * FROM filmes ORDER BY criado_em DESC";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streamflix</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="pg-principal.css">
    <link rel="stylesheet" href="pg-principal2.css">
    <style>
    .link {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 70px;
  height: 50px;
  border-radius: 50px;
  position: relative;
  z-index: 1;
  overflow: hidden;
  transform-origin: center left;
  transition: width 0.2s ease-in;
  text-decoration: none;
  color: inherit;
  &:before {
    position: absolute;
    z-index: -1;
    content: "";
    display: block;
    border-radius: 8px;
    width: 100%;
    height: 100%;
    top: 0;
    transform: translateX(100%);
    transition: transform 0.2s ease-in;
    transform-origin: center right;
    background-color: rgba(255, 255, 255, 0.1);
  }

  &:hover,
  &:focus {
    outline: 0;
    width: 130px;

    &:before,
    .link-title {
      transform: translateX(0);
      opacity: 1;
    }
  }
}

.link-icon {
  width: 28px;
  height: 28px;
  display: block;
  flex-shrink: 0;
  left: 18px;
  position: absolute;
  svg {
    width: 28px;
    height: 28px;
  }
}

.link-title {
  transform: translateX(100%);
  transition: transform 0.2s ease-in;
  transform-origin: center right;
  display: block;
  text-align: center;
  text-indent: 28px;
  width: 100%;
}
</style>
</head>
<body>
<div id="loading-screen">
    <div class="spinner"></div>
</div>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="pg-principal.php">Aurora</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                
                    <?php if ($is_logged_in && $is_admin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="upload.php">Adicionar Filme</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item d-flex align-items-center gap-3">
                                 <a href="chat.php" class="link">
                                    <span class="link-icon">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="192"
                                        height="192"
                                        fill="currentColor"
                                        viewBox="0 0 256 256"
                                    >
                                        <rect width="256" height="256" fill="none"></rect>
                                        <path
                                        d="M45.42853,176.99811A95.95978,95.95978,0,1,1,79.00228,210.5717l.00023-.001L45.84594,220.044a8,8,0,0,1-9.89-9.89l9.47331-33.15657Z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="16"
                                        ></path>
                                        <line
                                        x1="96"
                                        y1="112"
                                        x2="160"
                                        y2="112"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="16"
                                        ></line>
                                        <line
                                        x1="96"
                                        y1="144"
                                        x2="160"
                                        y2="144"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="16"
                                        ></line>
                                    </svg>
                                    </span>
                                    <span class="link-title">Chat</span>
                                </a>
    
                            <!-- Ícone de Notificação -->
                            <a href="notificacoes.php" class="nav-link">
                                <button class="button2">
                                    <svg class="bell" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
                                    </svg>
                                </button>
                            </a>
                            <!-- Ícone de Configurações -->
                            <div class="user-menu" style="position: relative;">
                                <button 
                                    class="config-button"
                                    title="Configurações"
                                    id="configToggle"
                                >
                                    ⚙
                                </button>
                                <!-- Menu de Configurações -->
                                <ul class="config-menu" id="configMenu">
                                    <li><a href="perfil.php" class="config-link">Perfil</a></li>
                                    <li><a href="lista_filmes.php" class="config-link">Explore</a></li>
                                    <li><a href="amigos.php" class="config-link">Amizades</a></li>
                                    <li><a href="historico.php" class="config-link">Histórico</a></li>
                                    <li><a href="favoritos.php" class="config-link">Favoritos</a></li>
                                    <li><a href="logout.php" class="config-link">Sair</a></li>
                                    
                                </ul>
                            </div>

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

    <div class="banner">
        <!-- Barra de pesquisa -->
        <div class="search-bar">
            <form method="GET" action="">
                <input 
                    type="search" 
                    name="q" 
                    value="<?php echo htmlspecialchars($search_query); ?>" 
                    placeholder="Pesquise por filmes..." 
                    aria-label="Search">
                <button type="submit">Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="container mt-4">
    <h1>Os mais recentes</h1>
    <div class="scrolling-wrapper">
        <?php 
        $filme_count = 0; // Contador de filmes exibidos
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()): 
                if ($filme_count >= 6) break; // Interrompe o loop após exibir 6 filmes
                $filme_count++; // Incrementa o contador
        ?>
                <div class="card">
                    <a href="detalhes.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?php echo $row['imagem']; ?>" class="card-img-top" alt="Imagem do Filme">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['titulo']; ?></h5>
                            <p class="card-text"><?php echo $row['descricao']; ?></p>
                        </div>
                    </a>
                </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <p>Não há filmes disponíveis no momento.</p>
        <?php endif; ?>
    </div>
</div>

    <script>    document.addEventListener("DOMContentLoaded", function () {
        const configToggle = document.getElementById("configToggle");
        const configMenu = document.getElementById("configMenu");

        // Alternar a visibilidade do menu ao clicar no botão de configurações
        configToggle.addEventListener("click", function (event) {
            event.stopPropagation(); // Impede que o clique feche imediatamente
            configMenu.style.display = (configMenu.style.display === "block") ? "none" : "block";
        });

        // Fechar o menu se clicar fora dele
        document.addEventListener("click", function (event) {
            if (!configMenu.contains(event.target) && !configToggle.contains(event.target)) {
                configMenu.style.display = "none";
            }
        });
    });
    document.onreadystatechange = function () {
        if (document.readyState === "complete") {
            setTimeout(() => {
                document.getElementById("loading-screen").classList.add("hidden");
            }, 1000); // 1 segundo antes de desaparecer
        }
    };

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
