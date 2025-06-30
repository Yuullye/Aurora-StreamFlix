<?php
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'streamflix';
$username = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id']; // Adiciona o ID do usuário na sessão
        $_SESSION['usuario'] = $usuario['username'];
        $_SESSION['email'] = $usuario['email'];

        // Verificar se é administrador com base no email
        $_SESSION['admin'] = ($email === 'admin@gmail.com');    

        // Redirecionar para a página principal
        header("Location: pg-principal.php");
        exit;
    } else {
        echo "<p style='color: red;'>Email ou senha inválidos.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="login.css">

</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Senha:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Entrar</button>


    </form>
    <p>Não tem uma conta? <a href="registro.php">Registre-se aqui</a></p>
    <model-viewer src="modelos/moedarian.glb"
              alt="Modelo 3D"
              auto-rotate
              camera-controls
              ar
              style="width: 20%; height: 250px;">
</model-viewer>

</body>

</html>
