<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
// Connect to db with db.php file and check access_to json in users table for logged in user
require_once 'db.php';

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
?>

<?php
$title = "Accueil";
require "head.php";
?>

<?php
require "header.php";
?>

<?php
// Fetch all groups from the database that the user has access to using the json array named groups containing the group ids and generate the group cards

$groupsData = json_decode($accessTo, true);
?>
<div class="container">
    <div class="row justify-content-center text-center mt-5">
        <?php
        // Loop through each group id and fetch the group name
        if (isset($groupsData['groups']) && is_array($groupsData['groups']) && sizeof($groupsData['groups']) > 0) {
            foreach ($groupsData['groups'] as $groupId) {
                $stmtGroup = $db->prepare("SELECT name FROM groups WHERE id = ?");
                $stmtGroup->bind_param("i", $groupId);
                $stmtGroup->execute();
                $stmtGroup->bind_result($groupName);
                $stmtGroup->fetch();
                $stmtGroup->close();

                require "groupcard.php";
            }
        } else { ?>
            <div class="alert alert-warning w-50 mx-auto">
                Vous n'avez accès à aucun groupe.
            </div>
        <?php } ?>
    </div>
</div>

<?php
require "footer.php";
?>