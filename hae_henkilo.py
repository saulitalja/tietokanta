import psycopg2

def hae_tieto(tietokantayhteys, HenkiloID):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "SELECT Etunimi, Sukunimi FROM Henkilot WHERE HenkiloID = %s;"
        cursor.execute(sql, (HenkiloID,))
        result = cursor.fetchone()
        if result:
            etunimi, sukunimi = result
            print(f"Henkilön tiedot: Etunimi: {etunimi}, Sukunimi: {sukunimi}")
        else:
            print("Henkilöä ei löydy ID:llä:", HenkiloID)
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

        # Kysytään käyttäjältä henkilön ID ja haetaan tiedot
        HenkiloID = input("Anna henkilön ID: ")
        hae_tieto(yhteys, HenkiloID)

        # Suljetaan tietokantayhteys
        yhteys.close()
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietokantayhteydessä:", error)

if __name__ == "__main__":
    main()
