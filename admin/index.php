<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
// Connect to db with db.php file and check access_to json in users table for logged in user
require_once '../db.php';

$userId = $_SESSION['id'];

$stmt = $db->prepare("SELECT access_to FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($accessTo);
$stmt->fetch();
$stmt->close();

if (empty($accessTo)) {
    $accessTo = array();
}

$accessTo = json_decode($accessTo, true);

$stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

?>

<?php
require "../head.php";
?>

<?php
require "../header.php";
?>

<?php
if ($is_admin == 1) {

?>

    <h1 class="text-center mt-5">Panneau Administratif</h1>

    <form method="POST" action="update.php">
        <div class="form-group">
            <label for="username">Nouveau nom d'utilisateur:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
        </div>
        <div class="form-group">
            <label for="email">Nouvel email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
        </div>
        <div class="form-group">
            <label for="password">Nouveau mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>

    <form method="POST" action="delete.php">
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
    </form>
<?php
} else {
    header('Location: ../index.php');
}
?>

<?php
require "../footer.php";
?>