import mysql.connector

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",  # Add your password if required
    database="Sangalodatabase"
)

cursor = db.cursor()

# Add multiple columns to the table
try:
    cursor.execute("""
     ALTER TABLE राजनीतिज्ञ 
     ADD COLUMN फोटो VARCHAR(2048),
     ADD COLUMN शिक्षा VARCHAR(100),
     ADD COLUMN Social_media VARCHAR(2048),
     ADD COLUMN उपलब्धिहरू VARCHAR(100),
     ADD COLUMN वैवाहिक_स्थिति VARCHAR(100),
     ADD COLUMN भाषा VARCHAR(100);
    """)
    # Replace 'column1', 'column2', 'column3' with the names of your new columns
    # Replace 'datatype' with the data types of your new columns (e.g., VARCHAR(100), INT, etc.)
    db.commit()
    print("Multiple columns added successfully!")
except mysql.connector.Error as err:
    print(f"Error: {err}")

db.close()