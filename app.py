from flask import Flask, request, jsonify
import psycopg2

app = Flask(__name__)

# Yhteys tietokantaan
def get_db_connection():
    return psycopg2.connect(
        dbname="postgres",
        user="postgres",
        password="postgres",
        host="localhost",
        port="5432"
    )

# Poista henkilö
def poista_henkilo(tietokantayhteys, HenkiloID):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "DELETE FROM Henkilot WHERE HenkiloID = %s;"
        cursor.execute(sql, (HenkiloID,))
        
        if cursor.rowcount > 0:
            tietokantayhteys.commit()
            return jsonify({"message": f"Henkilö ID {HenkiloID} poistettu onnistuneesti."}), 200
        else:
            return jsonify({"message": f"Henkilöä ID {HenkiloID} ei löytynyt."}), 404
    except (Exception, psycopg2.Error) as error:
        return jsonify({"message": "Virhe henkilön poistossa.", "error": str(error)}), 500

# Lisää henkilö
def lisaa_henkilo(tietokantayhteys, Etunimi, Sukunimi):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "INSERT INTO Henkilot (Etunimi, Sukunimi) VALUES (%s, %s);"
        cursor.execute(sql, (Etunimi, Sukunimi))
        tietokantayhteys.commit()
        return jsonify({"message": "Henkilö lisätty onnistuneesti!"}), 201
    except (Exception, psycopg2.Error) as error:
        return jsonify({"message": "Virhe henkilön lisäämisessä.", "error": str(error)}), 500

# Päivitä henkilö
def paivita_henkilo(tietokantayhteys, HenkiloID, Etunimi, Sukunimi):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "UPDATE Henkilot SET Etunimi = %s, Sukunimi = %s WHERE HenkiloID = %s;"
        cursor.execute(sql, (Etunimi, Sukunimi, HenkiloID))
        
        if cursor.rowcount > 0:
            tietokantayhteys.commit()
            return jsonify({"message": f"Henkilö ID {HenkiloID} päivitetty onnistuneesti."}), 200
        else:
            return jsonify({"message": f"Henkilöä ID {HenkiloID} ei löytynyt."}), 404
    except (Exception, psycopg2.Error) as error:
        return jsonify({"message": "Virhe henkilön päivityksessä.", "error": str(error)}), 500

# Hae henkilö ID:n perusteella (SELECT)
def hae_henkilo(tietokantayhteys, HenkiloID):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "SELECT HenkiloID, Etunimi, Sukunimi FROM Henkilot WHERE HenkiloID = %s;"
        cursor.execute(sql, (HenkiloID,))
        henkilo = cursor.fetchone()
        
        if henkilo:
            return jsonify({
                "HenkiloID": henkilo[0],
                "Etunimi": henkilo[1],
                "Sukunimi": henkilo[2]
            }), 200
        else:
            return jsonify({"message": f"Henkilöä ID {HenkiloID} ei löytynyt."}), 404
    except (Exception, psycopg2.Error) as error:
        return jsonify({"message": "Virhe henkilön haussa.", "error": str(error)}), 500

# Hae kaikki henkilöt (SELECT *)
def hae_kaikki_henkilot(tietokantayhteys):
    try:
        cursor = tietokantayhteys.cursor()
        sql = "SELECT HenkiloID, Etunimi, Sukunimi FROM Henkilot;"
        cursor.execute(sql)
        henkilot = cursor.fetchall()

        if henkilot:
            kaikki_henkilot = []
            for henkilo in henkilot:
                kaikki_henkilot.append({
                    "HenkiloID": henkilo[0],
                    "Etunimi": henkilo[1],
                    "Sukunimi": henkilo[2]
                })
            return jsonify(kaikki_henkilot), 200
        else:
            return jsonify({"message": "Ei henkilöitä löytynyt."}), 404
    except (Exception, psycopg2.Error) as error:
        return jsonify({"message": "Virhe kaikkien henkilöiden haussa.", "error": str(error)}), 500

# API-reitit

# Poista henkilö
@app.route('/poista_henkilo/<int:HenkiloID>', methods=['DELETE'])
def poista_henkilo_route(HenkiloID):
    yhteys = get_db_connection()
    response = poista_henkilo(yhteys, HenkiloID)
    yhteys.close()
    return response

# Lisää henkilö
@app.route('/lisaa_henkilo', methods=['POST'])
def lisaa_henkilo_route():
    data = request.get_json()
    Etunimi = data.get('Etunimi')
    Sukunimi = data.get('Sukunimi')

    if not Etunimi or not Sukunimi:
        return jsonify({"message": "Etunimi ja sukunimi ovat pakollisia."}), 400
    
    yhteys = get_db_connection()
    response = lisaa_henkilo(yhteys, Etunimi, Sukunimi)
    yhteys.close()
    return response

# Päivitä henkilö
@app.route('/paivita_henkilo/<int:HenkiloID>', methods=['PUT'])
def paivita_henkilo_route(HenkiloID):
    data = request.get_json()
    Etunimi = data.get('Etunimi')
    Sukunimi = data.get('Sukunimi')

    if not Etunimi or not Sukunimi:
        return jsonify({"message": "Etunimi ja sukunimi ovat pakollisia."}), 400
    
    yhteys = get_db_connection()
    response = paivita_henkilo(yhteys, HenkiloID, Etunimi, Sukunimi)
    yhteys.close()
    return response

# Hae henkilö ID:n perusteella
@app.route('/hae_henkilo/<int:HenkiloID>', methods=['GET'])
def hae_henkilo_route(HenkiloID):
    yhteys = get_db_connection()
    response = hae_henkilo(yhteys, HenkiloID)
    yhteys.close()
    return response

# Hae kaikki henkilöt
@app.route('/hae_kaikki_henkilot', methods=['GET'])
def hae_kaikki_henkilot_route():
    yhteys = get_db_connection()
    response = hae_kaikki_henkilot(yhteys)
    yhteys.close()
    return response

if __name__ == "__main__":
    app.run(debug=True)
