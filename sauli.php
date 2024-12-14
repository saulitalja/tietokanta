<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Henkilötiedot</title>
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
    </style>
</head>
<body>
    <h1 style="text-align: center;">Henkilötiedot</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Etunimi</th>
                <th>Sukunimi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Yhteysasetukset
            $host = 'localhost';
            $dbname = 'postgres';
            $username = 'postgres';
            $password = 'postgres';

            try {
                // Yhdistä tietokantaan
                $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Hae tiedot
                $query = $db->query('SELECT henkiloid, etunimi, sukunimi FROM henkilot');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);

                // Tulosta tiedot HTML-taulukkoon
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['henkiloid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['etunimi']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['sukunimi']) . '</td>';
                    echo '</tr>';
                }
            } catch (PDOException $e) {
                echo '<tr><td colspan="3">Virhe tietokantayhteydessä: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>
