import time
import random
from mysql.connector import Error
import mysql.connector

try:
    while True:
        device = "4"
        connection = mysql.connector.connect(host='localhost',
                                             database='raspberry',
                                             user='admin',
                                             password='admin')
        sql_select_Query = "select * from pump_automatic where device_id=" + device
        cursor = connection.cursor(dictionary=True)
        cursor.execute(sql_select_Query)
        records = cursor.fetchone()
        # print(records)
        if records["auto"] == 1:
            print("automatic on")
            timeon = records["time_on"]
            timeoff = records["time_off"]
            print("pump on")
            time.sleep(1)
            print("pump off ")
            time.sleep(1)
        elif records["auto"] == 0:
            print("automatic off")
            time.sleep(1)
            break

        # for row in records:
        #     print("Id = ", row[0], )
        #     print("Name = ", row[1])
        #     print("ppmmin  = ", row[2])
        #     print("ppmmax  = ", row[3], "\n")
except Error as e:
    print("Error reading data from MySQL table", e)
finally:
    if (connection.is_connected()):
        connection.close()
        cursor.close()
        print("MySQL connection is closed")
