from smbus import SMBus
import paho.mqtt.client as mqtt
import time
import sys

addr = 0x8 
bus = SMBus(1)
numb = 1
broker="maqiatto.com"
topic = "thuycanhiot@gmail.com/6=pump"

def on_connect(client, userdata, flags, rc):
    if rc==0:
        print("connected OK")
        client.subscribe(topic)
    else:
        print("Bad connection Returned code=",rc)
        
def on_message(client, userdata, msg):
    message = str(msg.payload).split("'")[1]
    print(msg.topic+ "\n" + message)
    if message == "1":
        bus.write_byte(addr, 0x1) 
    elif message == "0":
        bus.write_byte(addr, 0x0) 
    

client = mqtt.Client("python1") #create new instance
client.on_connect=on_connect  #bind call back function
client.on_message = on_message
client.username_pw_set(username="thuycanhiot@gmail.com",password="Lancuoi1234@")

print("Connecting to broker ",broker)
client.connect(broker,1883) #connect to broker
    
print("in Main Loop")
client.loop_forever()
