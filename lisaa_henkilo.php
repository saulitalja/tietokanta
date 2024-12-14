<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisää Henkilö</title>
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
        input[type="text"], input[type="submit"] {
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
    <h1 style="text-align: center;">Lisää henkilö</h1>
    <form action="lisaa_henkilo.php" method="POST">
        <label for="etunimi">Etunimi:</label>
        <input type="text" id="etunimi" name="etunimi" required>

        <label for="sukunimi">Sukunimi:</label>
        <input type="text" id="sukunimi" name="sukunimi" required>

        <input type="submit" value="Lisää henkilö">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Tietokantayhteyden asetukset
        $host = 'localhost';
        $dbname = 'postgres';
        $username = 'postgres';
        $password = 'postgres';

        // Lomakkeen tiedot
        $etunimi = htmlspecialchars($_POST['etunimi']);
        $sukunimi = htmlspecialchars($_POST['sukunimi']);

        try {
            // Yhdistä tietokantaan
            $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Lisää tiedot tauluun
            $stmt = $db->prepare('INSERT INTO henkilot (etunimi, sukunimi) VALUES (:etunimi, :sukunimi)');
            $stmt->execute([
                ':etunimi' => $etunimi,
                ':sukunimi' => $sukunimi
            ]);

            echo '<div class="message">Henkilö lisättiin onnistuneesti!</div>';
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
