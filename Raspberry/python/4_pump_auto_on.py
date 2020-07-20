import time
from mysql.connector import Error
import mysql.connector
# prepare query and data
query = """ UPDATE pump_automatic
            SET auto = 1
            WHERE device_id = 4 """
try:
    conn = mysql.connector.connect(host='localhost',
                                   database='raspberry',
                                   user='admin',
                                   password='admin')
    # update book title
    cursor = conn.cursor()
    cursor.execute(query)

    # accept the changes
    conn.commit()

except Error as error:
    print(error)

finally:
    cursor.close()
    conn.close()
