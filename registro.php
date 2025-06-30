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

// Registrar o administrador automaticamente (executado uma única vez)
$sql = "SELECT COUNT(*) FROM usuarios WHERE email = 'admin@gmail.com'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$admin_exists = $stmt->fetchColumn() > 0;

if (!$admin_exists) {
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (username, email, password) VALUES ('Admin', 'admin@gmail.com', :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $admin_password]);
    echo "<p style='color: green;'>Administrador criado com sucesso.</p>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografar a senha

    // Inserir dados no banco
    $sql = "INSERT INTO usuarios (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
        echo "Registro concluído com sucesso!";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao registrar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="registro.css">
</head>
<body>
<h2>Registro</h2>
<form method="POST">
    <label>Usuário:</label>
    <input type="text" name="username" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Senha:</label>
    <input type="password" name="password" required>

    <button type="submit">Registrar</button>
</form>
<p class="login-link">Já tem uma conta? <a href="login.php">Login</a></p>


</body>
</html>
