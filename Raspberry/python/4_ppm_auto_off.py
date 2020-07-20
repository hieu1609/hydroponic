from flask import Flask
import time
from flask_restful import Api, Resource, reqparse
from mysql.connector import Error
import mysql.connector
# prepare query and data
query = """ UPDATE ppm_automatic
            SET auto_mode = 0, auto status =0
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
