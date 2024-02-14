<?php
$config = array(
    'dbhost' => 'localhost',
    'dbname' => 'user',
    'dbport' => '3306',
    'dbuser' => 'root',
    'dbpass' => ''
  );




function getPDOlink($config) {

$dsn = 'mysql:dbname=' . $config['dbname'] . ';host=' . $config['dbhost'] . ';port=' . $config['dbport'];
var_dump($dsn);
    // ... votre implémentation pour obtenir une connexion PDO
 try {   // On instancie l'objet PDO :
  $db = new PDO($dsn, $config['dbuser'], $config['dbpass']);

  // On envoi nos requetes en utf8 :
  $db->exec("SET NAMES utf8");

  // On definit le mode de fetch par defaut :
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  return $db;

} catch (PDOException $e) {
  exit('BDD Erreur de connexion : ' . $e->getMessage());
}
}

session_start() ;


// Obtenez la connexion PDO en utilisant la fonction getPDOlink
$db = getPDOlink($config);

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Utilisez htmlspecialchars pour éviter les attaques XSS
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Utilisez des requêtes préparées pour éviter les attaques par injection SQL
    $query = "SELECT * FROM first_dbusers WHERE username = :username AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    // Récupérez le résultat
    $rows = $stmt->rowCount();

    if ($rows == 1) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit(); // Assurez-vous de terminer le script après la redirection
    } else {
        $message = 'Le nom est incorrect ou le mot de passe est incorrect';
    }
} else {
    $message = 'Veuillez fournir à la fois le nom d\'utilisateur et le mot de passe';
}



?>



<form method="post" name=login>
    <h1>connexion</h1>
    <input type="text" name="username"  placeholder="Nom d'utilisateur" ><br>
    <input type="password" name="password"   placeholder="Mot de passe"><br>

    <input type="submit" value="Connexion" name=submit><br>
    <?php if (!empty($message)) {?>
    <p><?php echo $message; ?></p>
    <?php  }?>

</form>