<?php
//empty all the variable that fill from the input in the form 
$FirstName = "";
$LastName = "";
$DateOfBirth = "";
$HireDate = "";
$Fonction = "";
$IdService = "";
$Salary = "";


$dsn = 'mysql:host=localhost;dbname=company';
$user = 'root';
$pass = '';

try {
    $DB = new PDO($dsn, $user, $pass);
    $DB->exec('USE company');
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // select the functions from table employe without repeat
    $fonctions = "SELECT DISTINCT fonction FROM employe";
    $Statement_f = $DB->prepare($fonctions);
    $Statement_f->execute();

    // select the services's name and id from table services 
    $services = "SELECT nomService ,idService  FROM services ";
    $Statement_s = $DB->prepare($services);
    $Statement_s->execute();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $DateOfBirth = $_POST['DateOfBirth'];
        $HireDate = $_POST['HireDate'];
        $Fonction = $_POST['Fonction'];
        $IdService = $_POST['Service'];
        $Salary = $_POST['Salary'];

        $newEmploye = "INSERT INTO employe (nom, prenom, dateDeNaissance, fonction, salaire, dateEmbauche, IdService) 
        VALUES (:lastName, :firstName, :dateOfBirth, :fonction, :salary, :hireDate, :idService)";

        $Statement_add = $DB->prepare($newEmploye);

        // Bind values to the placeholders
        $Statement_add->bindParam(':lastName', $LastName);
        $Statement_add->bindParam(':firstName', $FirstName);
        $Statement_add->bindParam(':dateOfBirth', $DateOfBirth);
        $Statement_add->bindParam(':fonction', $Fonction);
        $Statement_add->bindParam(':salary', $Salary);
        $Statement_add->bindParam(':hireDate', $HireDate);
        $Statement_add->bindParam(':idService', $IdService);

        $Statement_add->execute();
    }
} catch (PDOException $e) {
    echo 'failed ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Add New Employee </title>
</head>

<body>

    <a href="index.php" class="btn btn-secondary m-3">Back</a>
    <section class="container d-flex justify-content-center">
        <form action="" method="post" class="row g-3 w-50 border p-2 my-3">
            <div class="col-6">
                <label for="" class="form-label">First Name :</label><br>
                <input type="text" class="form-control" name="FirstName" required>
            </div>
            <div class="col-6">
                <label for="" class="form-label">Last Name :</label><br>
                <input type="text" class="form-control" name="LastName" required>
            </div>
            <div>
                <label for="" class="form-label">Date Of Birth :</label><br>
                <input type="date" class="form-control" name="DateOfBirth" required>
            </div>
            <div>
                <label for="" class="form-label">Fonction :</label><br>
                <select name="Fonction" id="" class="form-select" required>
                    <option value=".."></option>
                    <?php
                    // Reset the cursor position in the result set
                    $Statement_f->execute();

                    while ($row = $Statement_f->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['fonction']}' >" . $row['fonction'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="" class="form-label">Service :</label><br>
                <select name="Service" id="" class="form-select" required>
                    <option value=".."></option>
                    <?php
                    // Reset the cursor position in the result set
                    $Statement_s->execute();
                    
                    while ($row = $Statement_s->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idService']}' >" . $row['nomService'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="" class="form-label">Hire Date :</label><br>
                <input type="date" class="form-control" name="HireDate" required>
            </div>
            <div>
                <label for="" class="form-label">Salary :</label><br>
                <input type="number" class="form-control" name="Salary" required>
            </div>
            <div>
                <button type="submit" class="btn btn-success" name="Add">Add</button>
            </div>
        </form>
    </section>
</body>

</html>