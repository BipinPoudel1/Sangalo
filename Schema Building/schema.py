import mysql.connector

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    database="Sangalodatabase"
)

cursor = db.cursor()


# Create table
cursor.execute("""
CREATE TABLE राजनीतिज्ञ (
     प्रदेश VARCHAR(100),
     जिल्ला VARCHAR(100),
     स्थानीय_इकाई VARCHAR(100),
     स्थिति VARCHAR(100),
     राजनीतिक_पार्टी VARCHAR(100),
     नाम VARCHAR(100),
     लिङ्ग VARCHAR(100),
     उमेर VARCHAR(100),
     कुल_मतहरू VARCHAR(100),
     सम्पर्क_नम्बर VARCHAR(100),
     official_website VARCHAR(100)
)
""")

db.close()