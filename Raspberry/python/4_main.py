from smbus2 import SMBus
import paho.mqtt.client as mqtt
from mysql.connector import Error
import mysql.connector
import subprocess
import time
import sys

addr = 4
bus = SMBus(1)
broker_url = "maqiatto.com"
broker_port = 1883


def on_connect(client, userdata, flags, rc):
    print("Connected With Result Code "+rc)


def on_message(client, userdata, message):
    print("on message")
    mess = message.payload.decode()
    topic = str(message.topic).split("/")[1]
    idDevice = str(topic).split("=")[0]
    topic1 = str(topic).split("=")[1]
    dataSend = topic + "=" + mess
    print("Data Recieved: " + dataSend)
    if (topic1 == "ppmAuto"):
        # update database
        autoMode = str(mess).split("=")[0]
        idNutrient = str(mess).split("=")[1]
        ppm_auto(idDevice, idNutrient, autoMode)
    elif (topic1 == "pumpAuto"):
        # update database
        status = str(mess).split("=")[0]
        timeOn = str(mess).split("=")[1]
        timeOff = str(mess).split("=")[2]
        pump_auto(idDevice, timeOn, timeOff, status)
    else:
        if (topic1 == "pump"):
            pump_auto_off(idDevice)
        elif (topic1 == "waterIn"):
            ppm_auto_off(idDevice)
        elif (topic1 == "waterOut"):
            ppm_auto_off(idDevice)
        elif (topic1 == "ppm"):
            ppm_auto_off(idDevice)
        print("send topic message to arduino")
        dataSend = dataSend.encode()
        bus.write_i2c_block_data(addr, 0, dataSend)


def pump_auto(idDevice, timeOn, timeOff, status):
    query = """ UPDATE pump_automatic
            SET auto = %s, time_on = %s, time_off = %s
            WHERE device_id = %s """
    data = (status, timeOn, timeOff, idDevice)
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


def pump_auto_off(idDevice):
    query = """ UPDATE pump_automatic
            SET auto = %s
            WHERE device_id = %s """
    data = (0, idDevice)
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


def ppm_auto(idDevice, idNutrient, autoMode):
    # prepare query and data
    query = """ UPDATE ppm_automatic
                SET auto_mode = %s, nutrient_id = %s
                WHERE device_id = %s """
    data = (autoMode, idNutrient, idDevice)
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


def ppm_auto_off(idDevice):
    # prepare query and data
    query = """ UPDATE ppm_automatic
                SET auto_mode = %s, auto_status = %s
                WHERE device_id = %s """
    data = (0, 0, idDevice)
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
client.on_message = on_message
client.username_pw_set(username="thuycanhiot@gmail.com",
                       password="Lancuoi1234@")
client.connect(broker_url, broker_port)

client.subscribe("thuycanhiot@gmail.com/4=pump", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=mix", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=ppm", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=waterIn", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=waterOut", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=ppmAuto", qos=1)
client.subscribe("thuycanhiot@gmail.com/4=pumpAuto", qos=1)

client.subscribe("thuycanhiot@gmail.com/5=pump", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=mix", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=ppm", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=waterIn", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=waterOut", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=ppmAuto", qos=1)
client.subscribe("thuycanhiot@gmail.com/5=pumpAuto", qos=1)

client.loop_forever()
