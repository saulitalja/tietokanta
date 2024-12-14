import psycopg2

def hae_kaikki_tiedot(tietokantayhteys):
    try:
        cursor = tietokantayhteys.cursor()
        # Haetaan kaikki henkilöt
        sql = "SELECT HenkiloID, Etunimi, Sukunimi FROM Henkilot;"
        cursor.execute(sql)
        tulokset = cursor.fetchall()
        
        if tulokset:
            print("Kaikki henkilöt:")
            for rivi in tulokset:
                henkiloid, etunimi, sukunimi = rivi
                print(f"Henkilö ID: {henkiloid}, Etunimi: {etunimi}, Sukunimi: {sukunimi}")
        else:
            print("Ei löytynyt henkilöitä.")
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietoja haettaessa:", error)

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

        # Haetaan kaikki henkilöt ja tulostetaan
        hae_kaikki_tiedot(yhteys)

        # Suljetaan tietokantayhteys
        yhteys.close()
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietokantayhteydessä:", error)

if __name__ == "__main__":
    main()
