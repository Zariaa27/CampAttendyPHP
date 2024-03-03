<?php
$title = "Accueil";
require "head.php";
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
} else {
    require_once 'db.php';
    $userId = $_SESSION['id'];

    $stmt = $db->prepare("SELECT access_to FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($accessTo);
    $stmt->fetch();
    $stmt->close();

    $accessTo = json_decode($accessTo, true);
}
$groupId = $_GET['id'];
?>

<?php
require "header.php";
?>
<?php
if (!in_array($groupId, $accessTo['groups'])) {
    // Create alert and redirect to index.php after 10s
?>
    <div class="alert alert-danger justify-content-center text-center mt-5 mx-5" role="alert">
        <p>Vous n'avez pas accès à ce groupe. Vous allez être redirigés vers l'accueil dans 10 secondes.</p>
    </div>
<?php
    header('Refresh: 10; url=index.php');
} else { ?>
<?php


}
require "footer.php";
?>