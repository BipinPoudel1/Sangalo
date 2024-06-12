import pandas as pd
import mysql.connector

# Read data from Excel file
df = pd.read_excel('Data_Nepali_1.xlsx')

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    database="Sangalodatabase"
)
cursor = db.cursor()

# Insert data into the table
for index, row in df.iterrows():
    try:
        # Replace empty cells with None (NULL)
        row = row.where(pd.notnull(row), None)
        
        cursor.execute("""
        INSERT INTO राजनीतिज्ञ (प्रदेश, जिल्ला, स्थानीय_इकाई, स्थिति, राजनीतिक_पार्टी, नाम, लिङ्ग, उमेर, कुल_मतहरू, सम्पर्क_नम्बर, official_website)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """, (row['प्रदेश'], row['जिल्ला'], row['स्थानीय_इकाई'], row['स्थिति'], row['राजनीतिक_पार्टी'], row['नाम'], row['लिङ्ग'], row['उमेर'], row['कुल_मतहरू'], row['सम्पर्क_नम्बर'], row['official_website']))
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        print(f"Row data: {row}")

db.commit()

db.close()