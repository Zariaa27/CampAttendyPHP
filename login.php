<?php
session_start();
if (isset($_GET['logout'])) {
  session_destroy();
  $logout = true;
}

if (isset($_SESSION['id'])) {
  header('Location: index.php');
}
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once 'db.php'; // Make sure to create this file and establish a database connection inside it.

  if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die;
  }
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!empty($username) && !empty($password)) {
    $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_id, $db_username, $db_password);
    $stmt->fetch();
    $stmt->close();

    if (!empty($db_id) && !empty($db_username) && !empty($db_password)) {
      if (password_verify($password, $db_password)) {
        $_SESSION['id'] = $db_id;
        header('Location: index.php');
        exit();
      } else {
        $errorMessage = 'Invalid password.';
      }
    } else {
      $errorMessage = 'Invalid username.';
    }
  }
}
?>

<?php
$title = "Connexion";
require "head.php";
?>

<form class="w-50 mx-auto mt-5" method="POST">
  <div class="mb-3">
    <h1>Connexion</h1>
    <?php if (!empty($logout)) { ?>
      <div class="alert alert-success">
        Vous avez été déconnecté.
      </div>
    <?php } ?>
    <?php if (!empty($errorMessage)) { ?>
      <div class="alert alert-danger">
        <?php echo $errorMessage; ?>
      </div>
    <?php } ?>
    <label for="username" class="form-label">Nom d'utilisateur</label>
    <input type="text" class="form-control" id="username" name="username" aria-describedby="username">
    <div id="emailHelp" class="form-text">
      <p>Notre équipe ne vous demandera jamais de partager votre mot de passe avec eux.</p>
    </div>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Se souvenir de moi</label>
  </div>
  <button type="submit" class="btn btn-secondary">Connexion</button>
</form>

<?php
if (isset($_GET['logout'])) {
  require "notifications/logout.php";
}
?>

<?php
require "footer.php";
?>