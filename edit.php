<?php

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


    if (isset($_GET['matricule'])) {

        $matricule = $_GET['matricule'];

        $fillInput = "SELECT * FROM employe INNER JOIN services 
        ON employe.IdService=services.IdService
        WHERE matricule = :matricule ";

        $Statement = $DB->prepare($fillInput);
        $Statement->bindParam(':matricule', $matricule);
        $Statement->execute();
        $employe = $Statement->fetch(PDO::FETCH_ASSOC);

        if ($employe) {
            $_POST['FirstName'] = $employe['prenom'];
            $_POST['LastName'] = $employe['nom'];
            $_POST['DateOfBirth'] = $employe['dateDeNaissance'];
            $_POST['HireDate'] = $employe['dateEmbauche'];
            $_POST['Fonction'] = $employe['fonction'];
            $_POST['Service'] = $employe['IdService'];
            $_POST['Salary'] = $employe['salaire'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if(isset($_POST['save'])){

                 $edit = "UPDATE employe 
            SET nom = :lastName , 
            prenom=:firstName , 
            dateDeNaissance=:dateOfBirth , 
            fonction=:fonction , 
            salaire=:salary , 
            dateEmbauche =:hireDate , 
            IdService =:idService 
            WHERE matricule = :matricule ";

            $Statement_edit = $DB->prepare($edit);

            $Statement_edit->bindParam(':matricule', $matricule);
            $Statement_edit->bindParam(':lastName', $_POST['LastName'] );
            $Statement_edit->bindParam(':firstName',  $_POST['FirstName']);
            $Statement_edit->bindParam(':dateOfBirth',  $_POST['DateOfBirth']);
            $Statement_edit->bindParam(':fonction', $_POST['Fonction'] );
            $Statement_edit->bindParam(':salary', $_POST['Salary']);
            $Statement_edit->bindParam(':hireDate', $_POST['HireDate']);
            $Statement_edit->bindParam(':idService',  $_POST['Service'] );

            $Statement_edit->execute();


            }  
        }
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
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <a href="index.php" class="btn btn-secondary m-3">Back</a>
    <section class="container d-flex justify-content-center">
        <form action="" method="post" class="row g-3 w-50 border p-2 my-3">
            <div class="col-6">
                <label for="" class="form-label">First Name :</label><br>
                <input type="text" class="form-control" name="FirstName" required value="<?php echo $_POST['FirstName']; ?>">
            </div>
            <div class="col-6">
                <label for="" class="form-label">Last Name :</label><br>
                <input type="text" class="form-control" name="LastName" required value="<?php echo $_POST['LastName']; ?>">
            </div>
            <div>
                <label for="" class="form-label">Date Of Birth :</label><br>
                <input type="date" class="form-control" name="DateOfBirth" required value="<?php echo $_POST['DateOfBirth']; ?>">
            </div>
            <div>
                <label for="" class="form-label">Fonction :</label><br>
                <select name="Fonction" id="" class="form-select" required value="<?php echo $_POST['Fonction']; ?>">
                    <option value=".."></option>
                    <?php
                    // Reset the cursor position in the result set
                    $Statement_f->execute();

                    while ($row = $Statement_f->fetch(PDO::FETCH_ASSOC)) {
                        if ($_POST['Fonction'] === $row['fonction']) {
                            $F_selected = 'selected';
                        } else {
                            $F_selected = '';
                        }
                        echo "<option value='{$row['fonction']}' {$F_selected}>{$row['fonction']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="" class="form-label">Service :</label><br>
                <select name="Service" id="" class="form-select" required value="<?php echo $_POST['Service']; ?>">
                    <option value=".."></option>
                    <?php
                    // Reset the cursor position in the result set
                    $Statement_s->execute();

                    while ($row = $Statement_s->fetch(PDO::FETCH_ASSOC)) {
                        if ($_POST['Service'] === $row['nomService']) {
                            $S_selected = 'selected';
                        } else {
                            $S_selected = '';
                        }
                        echo "<option value='{$row['idService']}' {$S_selected} >" . $row['nomService'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="" class="form-label">Hire Date :</label><br>
                <input type="date" class="form-control" name="HireDate" required value="<?php echo $_POST['HireDate']; ?>">
            </div>
            <div>
                <label for="" class="form-label">Salary :</label><br>
                <input type="number" class="form-control" name="Salary" required value="<?php echo $_POST['Salary']; ?>">
            </div>
            <div>
                <button type="submit" class="btn btn-success" name="save">Save</button>
                <button type="button" onclick="window.location.href='index.php'" class="btn btn-primary">Cancel</button>
            </div>
        </form>
    </section>


</body>

</html>