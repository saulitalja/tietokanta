import psycopg2
#HenkiloID = input("Anna henkilön ID: ")
Etunimi = input("Anna henkilön etunimi: ")
Sukunimi = input("Anna henkilön sukunimi: ")

def lisaa_tieto(tietokantayhteys, Etunimi, Sukunimi):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "INSERT INTO Henkilot (Etunimi, Sukunimi) VALUES (%s, %s);"
        cursor.execute(sql, (Etunimi, Sukunimi))
        tietokantayhteys.commit()
        print("Tieto lisätty onnistuneesti!")
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietoa lisättäessä:", error)

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
        # Lisätään tiedot tietokantaan
        lisaa_tieto(yhteys, Etunimi, Sukunimi)
        # Suljetaan tietokantayhteys
        yhteys.close()
    except (Exception, psycopg2.Error) as error:
        print("Virhe tietokantayhteydessä:", error)

if __name__ == "__main__":
    main()
