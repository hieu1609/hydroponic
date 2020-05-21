#include <Wire.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <string.h>
#include <pt.h>

char ssid[] = "MHHH";
char pass[]  = "giahieu99";

static struct pt pt1; //thread

void recdata(char * tp, byte * conte, unsigned int length)
{
  String topic = String(tp);
  //byte -> char* -> String
  //remove ID
  int flag;
  for (int i = 0; i < topic.length(); i++) {
    if(topic.charAt(i) == '=') {
      flag = i;
    }
  }
  String newTopic = topic;
  newTopic.remove(0, flag + 1);
  Serial.println("topic: " + newTopic);
  String content = String((char*)conte);
  content.remove(length);
  Serial.println("content: " + content);
  //format message to send for UNO
  String data = newTopic + "," + content;
  Serial.println(data);
  char mess[20];
  data.toCharArray(mess,20);
  Wire.beginTransmission(8); /* begin with device address 8 */
  Wire.write(mess);
  Wire.endTransmission();  
}

WiFiClient client;
//server, port, [callback], client
PubSubClient MQTT("maqiatto.com", 1883, recdata, client);

void WifiConnect(char* ssid,char* pass){
  WiFi.begin(ssid, pass);
  Serial.print("Wifi connecting");
  while(1)
  {
    delay(500);
    Serial.print(".");
    if(WiFi.status()==WL_CONNECTED)
      break;
  }
  Serial.println("successfully connected wifi");
}

void ServerConnect(PubSubClient MQTT){
  Serial.println("Server connecting");
  while(1)
  {
    Serial.print(".");
    //connect with server
    if(MQTT.connect("clientname", "thuycanhiot@gmail.com", "Lancuoi1234@"))
      break;
    delay(500);
  }
  Serial.println("successfully connected server MQTT");
  MQTT.subscribe("thuycanhiot@gmail.com/6=pump");
  MQTT.subscribe("thuycanhiot@gmail.com/6=mix");
  MQTT.subscribe("thuycanhiot@gmail.com/6=ppm");
  MQTT.subscribe("thuycanhiot@gmail.com/6=waterIn");
  MQTT.subscribe("thuycanhiot@gmail.com/6=waterOut");
}

static int protothread1(struct pt *pt, int interval, PubSubClient MQTT, String message) {
  static unsigned long timestamp = 0;
  PT_BEGIN(pt);
  while(1) { // never stop 
    /* each time the function is called the second boolean
    *  argument "millis() - timestamp > interval" is re-evaluated
    *  and if false the function exits after that. */
    PT_WAIT_UNTIL(pt, millis() - timestamp > interval );
    timestamp = millis(); // take a new timestamp
    
    //convert string to char array & publish
    char buffer[64];
    message.toCharArray(buffer, 64);
    MQTT.publish("thuycanhiot@gmail.com/update",buffer);
  }
  PT_END(pt);
}

void setup() 
{
  Serial.begin(9600); /* begin serial for debug */
  //connect wifi
  WifiConnect(ssid, pass); 

  //connect server
  ServerConnect(MQTT);
  
  Wire.begin(D1, D2); /* join i2c bus with SDA=D1 and SCL=D2 of NodeMCU */
  
  //thread 1
  PT_INIT(&pt1);  
}

void loop() 
{
  //check wifi connection and MQTT connection 
  if (WiFi.waitForConnectResult() != WL_CONNECTED) {
    Serial.println("Not connected");
    WifiConnect(ssid, pass);
  } else {
    Serial.println("Connected");
    if (!MQTT.connected()) {
      Serial.println("MQTT ReConnect");
      MQTT.connect("clientname", "thuycanhiot@gmail.com", "Lancuoi1234@");
      MQTT.subscribe("thuycanhiot@gmail.com/6=pump");
      MQTT.subscribe("thuycanhiot@gmail.com/6=mix");
      MQTT.subscribe("thuycanhiot@gmail.com/6=ppm");
      MQTT.subscribe("thuycanhiot@gmail.com/6=waterIn");
      MQTT.subscribe("thuycanhiot@gmail.com/6=waterOut");
    }
    else {
      Serial.println("MQTT Connected");
    }
  }


  String message = "";
  MQTT.loop();
  
  Wire.beginTransmission(8); /* begin with device address 8 */

  Wire.requestFrom(8, 64); /* request & read data of size 13 from slave */
  while(Wire.available())
  {
    char c = Wire.read(); 
    if(c=='0'||c=='1'||c=='2'||c=='3'||c=='4'||c=='5'||c=='6'||c=='7'||c=='8'||c=='9'||c=='.'||c=='=')
      message+=c;
  }

  Serial.print(message);
  Serial.println();

  //publish update after 3s
  protothread1(&pt1, 3000, MQTT, message);
  
  Wire.endTransmission();    /* stop transmitting */
} 
