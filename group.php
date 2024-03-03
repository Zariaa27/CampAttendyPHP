<?php
$title = "Group";
require "head.php";
require_once 'db.php';

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
// Verify that the user has specified and has access to group
if (!isset($_GET['id'])) {
    header('Location: index.php');
} else {
    $groupId = $_GET['id'];

    $stmt = $db->prepare("SELECT access_to FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($accessTo);
    $stmt->fetch();
    $stmt->close();

    $accessTo = json_decode($accessTo, true);
    if (sizeof($accessTo) < 0) {
        header('Location: index.php');
    }
}
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
} else {
    

?>
<table class="table table-striped mt-5 w-75 mx-auto">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Âge</th>
            <th scope="col">Date d'anniversaire</th>
            <th scope="col">Parent</th>
            <th scope="col">Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch all children associated with the group and their parents' names
        $stmt = $db->prepare("
            SELECT students.*, parents.name AS parent_name
            FROM students
            LEFT JOIN parents ON students.parent = parents.id
            WHERE groupe = ?
        ");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = 0;
        // Iterate through each child and create a table row
        while ($child = $result->fetch_assoc()) {
            $count++;
            $age = date_diff(date_create($child['dob']), date_create('now'))->y;
            echo "<tr>";
            echo "<th scope='row'>" . htmlspecialchars($count) . "</th>";
            echo "<td>" . htmlspecialchars($child['name']) . "</td>";
            echo "<td>" . htmlspecialchars($age) . "</td>";
            echo "<td>" . htmlspecialchars($child['dob']) . "</td>";
            echo "<td>" . htmlspecialchars($child['parent_name']) . "</td>"; // Display the parent's name
            echo "<td>" . htmlspecialchars($child['notes']) . "</td>";
            echo "</tr>";
        }
        $stmt->close();
        ?>

    </tbody>
</table>

<?php
}
require "footer.php";
?>