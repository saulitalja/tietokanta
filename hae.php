<?php
// Määritellään HTTP-otsikot
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Sallii rajapinnan käytön mistä tahansa. Rajoita tarvittaessa.

$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'postgres';

try {
    // Yhdistetään tietokantaan
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Suoritetaan kysely
    $query = $db->query('SELECT * FROM henkilot');
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Palautetaan tulokset JSON-muodossa
    echo json_encode([
        'status' => 'success',
        'data' => $results
    ]);
} catch (PDOException $e) {
    // Virheen käsittely JSON-muodossa
    http_response_code(500); // Sisäinen palvelinvirhe
    echo json_encode([
        'status' => 'error',
        'message' => 'Virhe tietokantayhteydessä: ' . $e->getMessage()
    ]);
}
?>
