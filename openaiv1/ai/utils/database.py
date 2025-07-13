import mysql.connector
from mysql.connector import Error

def create_connection():
    """Create a connection to a MySQL database."""
    connection = None
    try:
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="Mazeluna13",
            database="Jualin"
        )
        print("MySQL Database connection successful")
    except Error as e:
        print(f"The error '{e}' occurred")
    return connection

