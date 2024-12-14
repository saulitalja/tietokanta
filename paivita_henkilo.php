<?php
// Tietokantayhteyden asetukset
$host = "localhost"; // PostgreSQL-palvelimen osoite
$port = "5432";      // PostgreSQL-portti (oletus)
$dbname = "postgres"; // Tietokannan nimi
$user = "postgres";  // Käyttäjänimi
$password = "postgres";      // Salasana

// Luo yhteys PostgreSQL:ään
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Tarkista yhteys
if (!$conn) {
    die("Yhteyden muodostaminen epäonnistui: " . pg_last_error());
}

// Tarkista onko id ja muut kentät lähetetty lomakkeella
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $henkiloid = $_POST['henkiloid'];  // Oletetaan, että id lähetetään lomakkeella
    $etunimi = $_POST['etunimi'];      // Etunimi
    $sukunimi = $_POST['sukunimi'];    // Sukunimi

    // Päivitä henkilot-taulu
    $sql = "UPDATE henkilot SET etunimi = $1, sukunimi = $2 WHERE henkiloid = $3";
    $result = pg_query_params($conn, $sql, array($etunimi, $sukunimi, $henkiloid));

    if ($result) {
        echo "Tietoja on päivitetty onnistuneesti.";
    } else {
        echo "Virhe tietojen päivityksessä: " . pg_last_error($conn);
    }
}

pg_close($conn); // Sulje yhteys
?>

<!-- HTML-lomake -->
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Päivitä Henkilö</title>
</head>
<body>
    <h1>Päivitä Henkilön Tiedot</h1>
    <form action="paivita_henkilo.php" method="post">
        <label for="henkiloid">Henkilön ID:</label>
        <input type="number" id="henkiloid" name="henkiloid" required><br><br>
        
        <label for="etunimi">Etunimi:</label>
        <input type="text" id="etunimi" name="etunimi" required><br><br>

        <label for="sukunimi">Sukunimi:</label>
        <input type="text" id="sukunimi" name="sukunimi" required><br><br>

        <input type="submit" value="Päivitä">
    </form>
</body>
<div class="links">
        <a href="index.php">Etusivu</a>
    </div>
</html>
