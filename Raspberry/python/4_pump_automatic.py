import time
import random
from mysql.connector import Error
import mysql.connector
from smbus2 import SMBus

addr = 4
bus = SMBus(1)

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

        if records["auto"] == 1:
            print("automatic on")
            timeon = records["time_on"]
            timeoff = records["time_off"]
            print("pump on")
            dataSend = device + "=pump=1"
            dataSend = dataSend.encode()
            bus.write_i2c_block_data(addr, 0, dataSend)
            time.sleep(timeon)
            print("pump off ")
            dataSend = device + "=pump=0"
            dataSend = dataSend.encode()
            bus.write_i2c_block_data(addr, 0, dataSend)
            time.sleep(timeoff)
        elif records["auto"] == 0:
            print("automatic off")
            time.sleep(1)

except Error as e:
    print("Error reading data from MySQL table", e)
finally:
    if (connection.is_connected()):
        connection.close()
        cursor.close()
        print("MySQL connection is closed")
