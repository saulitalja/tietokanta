<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poista Henkilö</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }
        .message {
            margin: 20px auto;
            max-width: 400px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Poista henkilö</h1>
    <form action="poista_henkilo.php" method="POST">
        <label for="id">Henkilön ID:</label>
        <input type="number" id="id" name="id" required>

        <input type="submit" value="Poista henkilö">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Tietokantayhteyden asetukset
        $host = 'localhost';
        $dbname = 'postgres';
        $username = 'postgres';
        $password = 'postgres';

        // Lomakkeen tiedot
        $id = intval($_POST['id']); // Varmista, että ID on kokonaisluku

        try {
            // Yhdistä tietokantaan
            $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Poista henkilö ID:n perusteella
            $stmt = $db->prepare('DELETE FROM henkilot WHERE henkiloid = :id');
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                echo '<div class="message">Henkilö poistettiin onnistuneesti!</div>';
            } else {
                echo '<div class="message">Henkilöä ID:llä ' . htmlspecialchars($id) . ' ei löytynyt.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="message">Virhe tietokantayhteydessä: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
    ?>
<div class="links">
        <a href="index.php">Etusivu</a>
    </div>

</body>
</html>
