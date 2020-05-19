#include <Wire.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <string.h>

char ssid[] = "MHHH";
char pass[]  = "giahieu99";

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
PubSubClient MQTT("m24.cloudmqtt.com", 15217, recdata, client);

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

void WifiReConnect(char* ssid,char* pass){
  WiFi.begin(ssid, pass);
}

void ServerConnect(PubSubClient MQTT){
  Serial.println("Server connecting");
  while(1)
  {
    Serial.print(".");
    //connect with server
    if(MQTT.connect("clientname", "tmlgemnz", "7fub13-eRIeR"))
      break;
    delay(500);
  }
  Serial.println("successfully connected server MQTT");
  MQTT.subscribe("6=pump");
  MQTT.subscribe("6=mix");
  MQTT.subscribe("6=ppm");
  MQTT.subscribe("6=waterIn");
  MQTT.subscribe("6=waterOut");
}

void ServerReConnect(PubSubClient MQTT){
  MQTT.connect("clientname", "tmlgemnz", "7fub13-eRIeR");
  MQTT.subscribe("6=pump");
  MQTT.subscribe("6=mix");
  MQTT.subscribe("6=ppm");
  MQTT.subscribe("6=waterIn");
  MQTT.subscribe("6=waterOut");
}

void setup() 
{
  Serial.begin(9600); /* begin serial for debug */
  //connect wifi
  WifiConnect(ssid, pass); 

  //connect server
  ServerConnect(MQTT);
  
  Wire.begin(D1, D2); /* join i2c bus with SDA=D1 and SCL=D2 of NodeMCU */
}

void loop() 
{
  //check wifi connection and MQTT connection 
  if (WiFi.waitForConnectResult() != WL_CONNECTED) {
    Serial.println("Not connected");
    WifiReConnect(ssid, pass);
  } else {
    Serial.println("Connected");
  }
  if (!MQTT.connected()) {
    Serial.println("MQTT ReConnect");
    MQTT.connect("client", "thuycanhiot@gmail.com", "Lancuoi1234@");
    MQTT.subscribe("6=pump");
    MQTT.subscribe("6=mix");
    MQTT.subscribe("6=ppm");
    MQTT.subscribe("6=waterIn");
    MQTT.subscribe("6=waterOut");
    //ServerReConnect(MQTT);
  }
  else {
    Serial.println("MQTT Connected");
  }

  String s = "";
  MQTT.loop();
  
  Wire.beginTransmission(8); /* begin with device address 8 */

  Wire.requestFrom(8, 64); /* request & read data of size 13 from slave */
  while(Wire.available())
  {
    char c = Wire.read(); 
    if(c=='0'||c=='1'||c=='2'||c=='3'||c=='4'||c=='5'||c=='6'||c=='7'||c=='8'||c=='9'||c=='.'||c=='=')
      s+=c;
  }

  Serial.print(s);
  Serial.println();
  char buffer[64];
  s.toCharArray(buffer, 64);

  MQTT.publish("update",buffer);
  Wire.endTransmission();    /* stop transmitting */
  delay(500);
} 
