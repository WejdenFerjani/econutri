import requests
import json

# URL de votre API
url = "http://localhost:5000/predict"

# Données de test
test_data = {
    "Pregnancies": 2,
    "Glucose": 138,
    "BloodPressure": 62,
    "SkinThickness": 35,
    "Insulin": 0,
    "BMI": 33.6,
    "DiabetesPedigreeFunction": 0.127,
    "Age": 47
}

try:
    # Envoi de la requête POST
    response = requests.post(url, json=test_data)
    
    # Affichage des résultats
    print(f"Status Code: {response.status_code}")
    print(f"Response: {response.json()}")
    
except requests.exceptions.RequestException as e:
    print(f"Error: {e}")