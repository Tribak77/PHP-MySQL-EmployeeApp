<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <section class="container">
        <?php
        $dsn = 'mysql:host=localhost;dbname=company';
        $user = 'root';
        $pass = '';

        try {
            $DB = new PDO($dsn, $user, $pass);
            $DB->exec('USE company');
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM employe INNER JOIN services on employe.IdService = services.IdService";
            $Statement = $DB->prepare($sql);
            $Statement->execute();

            echo "<a class='btn btn-primary m-1' href='AddNewEmployee.php'> Add New Employee</a>";
            echo '<table class="table table-striped">';
            echo "<tr>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Date De Naissance</th>
        <th>Fonction</th>
        <th>Salaire</th>
        <th>Service</th>
        <th>Modify/Delete</th>
        </tr>";

            while ($row = $Statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['matricule'] . "</td>";
                echo "<td>" . $row['nom'] . "</td>";
                echo "<td>" . $row['prenom'] . "</td>";
                echo "<td>" . $row['dateDeNaissance'] . "</td>";
                echo "<td>" . $row['fonction'] . "</td>";
                echo "<td>" . $row['salaire'] . "</td>";
                echo "<td>" . $row['nomService'] . "</td>";
                echo "<td>";
                echo "<form method='GET'>";
                echo "<a class='btn btn-primary m-1' href='edit.php?matricule={$row['matricule']}'  name='edit'> Edit  </a>";
                echo "<button class='btn btn-danger m-1' type='submit' name='delete' value='{$row['matricule']}'>Delete</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";


            $count_employe = "SELECT COUNT(*) FROM employe";
            $Stat = $DB->prepare($count_employe);
            $Stat->execute();
            $count = $Stat->fetchColumn();
            echo "<div  class='border border-2 w-25'>" . "Total employees : " . $count . "</div>";


            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['delete'])) {
                    $matricule = $_GET['delete'];

                    $sql = "DELETE FROM employe WHERE matricule = $matricule";
                    $Statement = $DB->prepare($sql);
                    $Statement->execute();

                    header("Location: http://localhost/MyProjects/exDATABASE/index.php");
                }
            } else {
                echo "Invalid request method.";
            }
        } catch (PDOException $e) {
            echo 'failed ' . $e->getMessage();
        }


        ?>
    </section>

</body>

</html>