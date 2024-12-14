import psycopg2

def poista_henkilo(tietokantayhteys, HenkiloID):
    try:
        cursor = tietokantayhteys.cursor()
        # Poistetaan henkilö ID:n perusteella
        sql = "DELETE FROM Henkilot WHERE HenkiloID = %s;"
        cursor.execute(sql, (HenkiloID,))
        
        # Tarkistetaan, onko rivi poistettu
        if cursor.rowcount > 0:
            print(f"Henkilö ID: {HenkiloID} poistettu onnistuneesti.")
        else:
            print(f"Henkilöä ID: {HenkiloID} ei löytynyt, ei poistettu.")
        
        tietokantayhteys.commit()
    except (Exception, psycopg2.Error) as error:
        print("Virhe henkilön poistossa:", error)

def main():
    try:
        # Yhdistetään tietokantaan
        yhteys = psycopg2.connect(
            dbname="postgres",
            user="postgres",
            password="postgres",
            host="localhost",
            port="5432"
        )

        # Kysytään käyttäjältä poistettavan henkilön ID
        HenkiloID = input("Anna poistettavan henkilön ID: ")
        poista_henkilo(yhteys, HenkiloID)

        # Suljetaan tietokantayhteys
        yhteys.close()
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietokantayhteydessä:", error)

if __name__ == "__main__":
    main()
