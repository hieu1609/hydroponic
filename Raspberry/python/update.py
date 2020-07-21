from smbus2 import SMBus
import paho.mqtt.client as mqtt
from mysql.connector import Error
import mysql.connector
import subprocess
import time
import sys

# connect mysql
addr = 7
bus = SMBus(1)
broker_url = "maqiatto.com"
broker_port = 1883


def on_connect(client, userdata, flags, rc):
    print("Connected With Result Code "+rc)


def update_sensor(device_id, temperature, humidity, light, EC, PPM, water, pump, water_in, water_out, mix):
    # prepare query and data
    query = """ UPDATE sensors
                SET temperature = %s, humidity = %s, light = %s, EC = %s, PPM = %s, water = %s, pump = %s, water_in = %s, water_out = %s, mix = %s 
                WHERE device_id = %s """
    data = (temperature, humidity, light, EC, PPM, water,
            pump, water_in, water_out, mix, device_id)
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='raspberry',
                                       user='admin',
                                       password='admin')
        # update book title
        cursor = conn.cursor()
        cursor.execute(query, data)

        # accept the changes
        conn.commit()

    except Error as error:
        print(error)

    finally:
        cursor.close()
        conn.close()


client = mqtt.Client()
client.on_connect = on_connect
client.username_pw_set(username="thuycanhiot@gmail.com",
                       password="Lancuoi1234@")

while True:
    # update sensors
    dataSensor = ""
    number = bus.read_i2c_block_data(addr, 0, 32)
    dataRe = ("".join(map(chr, number)))
    for x in range(0, len(dataRe)):
        if dataRe[x] == "=":
            dataSensor += dataRe[x]
        elif dataRe[x] == "0":
            dataSensor += dataRe[x]
        elif dataRe[x] == "1":
            dataSensor += dataRe[x]
        elif dataRe[x] == "2":
            dataSensor += dataRe[x]
        elif dataRe[x] == "3":
            dataSensor += dataRe[x]
        elif dataRe[x] == "4":
            dataSensor += dataRe[x]
        elif dataRe[x] == "5":
            dataSensor += dataRe[x]
        elif dataRe[x] == "6":
            dataSensor += dataRe[x]
        elif dataRe[x] == "7":
            dataSensor += dataRe[x]
        elif dataRe[x] == "8":
            dataSensor += dataRe[x]
        elif dataRe[x] == "9":
            dataSensor += dataRe[x]
    print(dataSensor)
    client.connect(broker_url, broker_port)
    client.publish("thuycanhiot@gmail.com/update",
                   payload=dataSensor, qos=1, retain=False)
    sen = dataSensor.split("=")
    update_sensor(sen[0], sen[1], sen[2], sen[3], sen[4], sen[5],
                  sen[6], sen[7][0], sen[7][1], sen[7][2], sen[7][3])

    # update pump automatic
    connection = mysql.connector.connect(host='localhost',
                                         database='raspberry',
                                         user='admin',
                                         password='admin')
    sql_select_Query = "select * from pump_automatic"
    cursor = connection.cursor(dictionary=True)
    cursor.execute(sql_select_Query)
    records = cursor.fetchall()
    for row in records:
        pumpUpdate = str(row["device_id"])+"="+str(row["time_on"]) + \
            "="+str(row["time_off"])+"="+str(row["auto"])
        print(pumpUpdate)
        client.connect(broker_url, broker_port)
        client.publish("thuycanhiot@gmail.com/updatePump",
                       payload=pumpUpdate, qos=1, retain=False)
    cursor.close()

    # update ppm automatic
    connection = mysql.connector.connect(host='localhost',
                                         database='raspberry',
                                         user='admin',
                                         password='admin')
    sql_select_Query = "select * from ppm_automatic"
    cursor = connection.cursor(dictionary=True)
    cursor.execute(sql_select_Query)
    records = cursor.fetchall()
    for row in records:
        ppmUpdate = str(row["device_id"])+"="+str(row["nutrient_id"]) + \
            "="+str(row["auto_mode"])+"="+str(row["auto_status"])
        print(ppmUpdate)
        client.connect(broker_url, broker_port)
        client.publish("thuycanhiot@gmail.com/updatePpm",
                       payload=ppmUpdate, qos=1, retain=False)
    cursor.close()

    time.sleep(3)
