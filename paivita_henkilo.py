import psycopg2

def paivita_henkilo(tietokantayhteys, HenkiloID, Etunimi, Sukunimi):
    try:
        cursor = tietokantayhteys.cursor()
        # Päivitetään henkilön etunimi ja sukunimi ID:n perusteella
        sql = "UPDATE Henkilot SET Etunimi = %s, Sukunimi = %s WHERE HenkiloID = %s;"
        cursor.execute(sql, (Etunimi, Sukunimi, HenkiloID))
        
        # Tarkistetaan, onko rivi päivitetty
        if cursor.rowcount > 0:
            print(f"Henkilön tiedot päivitetty ID: {HenkiloID}. Etunimi: {Etunimi}, Sukunimi: {Sukunimi}")
        else:
            print(f"Henkilöä ID: {HenkiloID} ei löytynyt, ei päivitetty.")
        
        tietokantayhteys.commit()
    except (Exception, psycopg2.Error) as error:
        print("Virhe henkilön päivityksessä:", error)

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

        # Kysytään käyttäjältä henkilön ID, etunimi ja sukunimi
        HenkiloID = input("Anna päivitettävän henkilön ID: ")
        Etunimi = input("Anna henkilön uusi etunimi: ")
        Sukunimi = input("Anna henkilön uusi sukunimi: ")

        # Päivitetään henkilön tiedot
        paivita_henkilo(yhteys, HenkiloID, Etunimi, Sukunimi)

        # Suljetaan tietokantayhteys
        yhteys.close()
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietokantayhteydessä:", error)

if __name__ == "__main__":
    main()
