from smbus2 import SMBus
import paho.mqtt.client as mqtt
import subprocess
import time
import sys

#connect mysql
addr = 7
bus = SMBus(1)
broker_url = "maqiatto.com"
broker_port = 1883

def on_connect(client, userdata, flags, rc):
    print("Connected With Result Code "+rc)

client = mqtt.Client()
client.on_connect = on_connect
client.username_pw_set(username="thuycanhiot@gmail.com",password="Lancuoi1234@")

while True:
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
    print (dataSensor)
    client.connect(broker_url, broker_port)
    client.publish("thuycanhiot@gmail.com/update", payload=dataSensor, qos=1, retain=False)
    time.sleep(3)

