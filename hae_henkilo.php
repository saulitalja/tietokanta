<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hae henkilö</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            text-align: center;
            margin: 20px;
        }
        .form-container input {
            padding: 8px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Hae henkilö</h1>

    <!-- Henkilön ID:n syöttö -->
    <div class="form-container">
        <form method="GET" action="">
            <label for="henkiloid">Syötä henkilön ID:</label>
            <input type="text" id="henkiloid" name="henkiloid" required>
            <input type="submit" value="Hae">
        </form>
    </div>

    <?php
    // Yhteysasetukset
    $host = 'localhost';
    $dbname = 'postgres';
    $username = 'postgres';
    $password = 'postgres';

    if (isset($_GET['henkiloid'])) {
        $henkiloid = $_GET['henkiloid'];

        try {
            // Yhdistä tietokantaan
            $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Valmistele ja suorita kysely ID:n perusteella
            $stmt = $db->prepare('SELECT henkiloid, etunimi, sukunimi FROM henkilot WHERE henkiloid = :henkiloid');
            $stmt->bindParam(':henkiloid', $henkiloid, PDO::PARAM_INT);
            $stmt->execute();

            // Tarkista, löytyikö henkilö
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<table>';
                echo '<thead><tr><th>ID</th><th>Etunimi</th><th>Sukunimi</th></tr></thead>';
                echo '<tbody>';
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['henkiloid']) . '</td>';
                echo '<td>' . htmlspecialchars($row['etunimi']) . '</td>';
                echo '<td>' . htmlspecialchars($row['sukunimi']) . '</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="text-align: center; color: red;">Henkilöä ei löytynyt ID:llä ' . htmlspecialchars($henkiloid) . '.</p>';
            }
        } catch (PDOException $e) {
            echo '<p style="text-align: center; color: red;">Virhe tietokantayhteydessä: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    ?>
<div class="links">
        <a href="index.php">Etusivu</a>
    </div>

</body>
</html>
