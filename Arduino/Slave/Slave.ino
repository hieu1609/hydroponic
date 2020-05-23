#include <Wire.h>
#include <DHT.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <pt.h>

const unsigned int TRIG_PIN = 13;
const unsigned int ECHO_PIN = 12;
const int DHTPIN = 2;       //Đọc dữ liệu từ DHT11 ở chân 2 trên mạch Arduino
const int DHTTYPE = DHT11;  //Khai báo loại cảm biến, có 2 loại là DHT11 và DHT22
const int RelayPpm = 7; //Relay Pha che
const int RelayWaterIn = 8; // Relay Bom nuoc vao thung
const int RelayMix =9; // Relay motor quay tron hon hop dung dich ding duong
const int RelayWaterOut = 10;// Relay bom nuoc ra khoi thung
const int RelayPump = 11; // Relay bom nuoc len cho dan thuy canh

static struct pt pt1; //thread

DHT dht(DHTPIN, DHTTYPE);
const int Light =  A2;
String dataSend;
const unsigned int BAUD_RATE = 9600;
/////EC
#define ONE_WIRE_BUS 5
int R1 = 850;
int Ra = 25; //Resistance of powering Pins
int ECPin = A0;
int ECGround = A1; //=> Cam vs GND
int ECPower = A3;
int relayStatus=0;
int waterInStatus=0;
int waterOutStatus=0;
int mixStatus=0;
float distance1;
float distancelast;
int distancesend;
int SlaveReceived=0;
//*********** Converting to ppm [Learn to use EC it is much better**************//
// Hana      [USA]        PPMconverion:  0.5
// Eutech    [EU]          PPMconversion:  0.64
//Tranchen  [Australia]  PPMconversion:  0.7
// Why didnt anyone standardise this?
float PPMconversion = 0.7;
//*************Compensating for temperature ************************************//
//The value below will change depending on what chemical solution we are measuring
//0.019 is generaly considered the standard for plant nutrients [google "Temperature compensation EC" for more info
float TemperatureCoef = 0.019; //this changes depending on what chemical we are measuring
//********************** Cell Constant For Ec Measurements *********************//
//Mine was around 2.9 with plugs being a standard size they should all be around the same
//But If you get bad readings you can use the calibration script and fluid to get a better estimate for K
float K = 2.7;
//************ Temp Probe Related *********************************************//
OneWire oneWire(ONE_WIRE_BUS);// Setup a oneWire instance to communicate with any OneWire devices
DallasTemperature sensors(&oneWire);// Pass our oneWire reference to Dallas Temperature.
float Temperature = 0;
float EC = 0;
float EC25 = 0;
int ppm = 0;

int checkppm = 0;
float raw = 0;
float Vin = 5;
float Vdrop = 0;
float Rc = 0;
float buffer1 = 0;
/////
float distance;
void setup() {
  Wire.begin(8);                /* join i2c bus with address 8 */
  Wire.onReceive(receiveEvent); /* register receive event */
  Wire.onRequest(requestEvent); /* register request event */

  PT_INIT(&pt1);  //thread 1
  
  pinMode(RelayPpm, OUTPUT); //7->11
  pinMode(RelayWaterIn, OUTPUT);
  pinMode(RelayWaterOut, OUTPUT);
  pinMode(RelayPump, OUTPUT);
  pinMode(RelayMix, OUTPUT);
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  /////////EC////////
  pinMode(ECPin, INPUT);
  pinMode(ECPower, OUTPUT); //Setting pin for sourcing current
  pinMode(ECGround, OUTPUT); //setting pin for sinking current
  digitalWrite(ECGround, LOW); //We can leave the ground connected permanantly
  pinMode(Light, INPUT);
  delay(100);// gives sensor time to settle
  sensors.begin();
  delay(100);
  R1 = (R1 + Ra); // Taking into acount Powering Pin Resitance
  //////////////////
  Serial.begin(9600);           /* start ser-ial for debug */
  dht.begin(); // Khởi động cảm biến DHT11, (Nhiệt độ, độ ẩm)
}

void loop() {
  if(checkppm == 1){
    digitalWrite(RelayPpm, HIGH);
    delay(1000);
    digitalWrite(RelayPpm, LOW);
    checkppm = 0;
  }
  int hum = dht.readHumidity();    //Đọc độ ẩm
  float temp = dht.readTemperature(); //Đọc nhiệt độ
  int lig = analogRead(Light);
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);
  float duration = pulseIn(ECHO_PIN, HIGH);
  distance1 = duration / 29.412 / 2;
  distance = 100 - ((distance1 - 3)*100/16); //Min 3cm, Max 19 cm
  distancelast = roundf(distance * 100) / 100;
  if(distancelast < 0){
    distancelast = 0;
  }
  if(distancelast >100){
    distancelast = 100;
  }
  distancesend = distancelast;
  sensors.requestTemperatures();// Send the command to get temperatures
  Temperature = sensors.getTempCByIndex(0); //Stores Value in Variable
  /////////////////////////////EC PPM
  protothread1(&pt1, 5000);          //Calls Code to Go into GetEC() Loop [Below Main Loop] dont call this more than 1/5 hhz [once every five seconds] or you will polarise the water
  // id, device_id, temperature, humidity, light, EC, PPM, water, pump, water_in, water_out, mix
  //  temp = 30.25;
  //  hum = 68;
  dataSend = "6="+(String)temp+"="+(String)hum+"="+(String)lig+"="+(String)EC25+"="+(String)ppm+"="+(String)distancesend+"="+(String)relayStatus+"="+(String)waterInStatus+"="+(String)waterOutStatus+"="+(String)mixStatus;
  //  dataSend = "6=26.11=42=558=1.40=753=56=1=0=0=0";
  Serial.println(dataSend); 
  delay(1000); 
}

// function that executes whenever data is received from master
void receiveEvent(int howMany) {
  String data;
  while (0 < Wire.available()) {
    char SlaveReceived = Wire.read();/* receive byte as a character */
    data += SlaveReceived; /* print the character */
  }
  int flag;
  for (int i = 0; i < data.length(); i++) {
    if(data.charAt(i) == ',') {
      flag = i;
    }
  }
  String topic = data;
  String mess = data;
  topic.remove(flag);
  mess.remove(0, flag + 1);
  if (topic == "pump") {
    if (mess == "1") {
      //Turn on pump
      Serial.println("Turn on pump");
      digitalWrite(RelayPump, HIGH);
      relayStatus=1;
    }
    else if (mess == "0") {
      //Turn off pump
      Serial.println("Turn off pump");
      digitalWrite(RelayPump, LOW);
      relayStatus=0;
    }
  }
  else if (topic == "ppm") {
     if (mess == "1") {
      //Turn on ppm
      Serial.println("Turn on ppm");
        checkppm = 1;
    }
    else if (mess == "0") {
      //Turn off ppm
      Serial.println("Turn off ppm");
      digitalWrite(RelayPpm, LOW);
    }
  }
  else if (topic == "waterIn") {
    if (mess == "1") {
      //Turn on waterIn RelayWaterIn
      Serial.println("Turn on waterIn");
      digitalWrite(RelayWaterIn, HIGH);
      waterInStatus=1;
    }
    else if (mess == "0") {
      //Turn off waterIn
      Serial.println("Turn off waterIn");
      digitalWrite(RelayWaterIn, LOW);
      waterInStatus=0;
    }
  }
  else if (topic == "waterOut") {
    if (mess == "1") {
      //Turn on waterOut
      Serial.println("Turn on waterOut");
      digitalWrite(RelayWaterOut, HIGH);
      waterOutStatus=1;
    }
    else if (mess == "0") {
      //Turn off waterOut
      Serial.println("Turn off waterOut");
      digitalWrite(RelayWaterOut, LOW);
      waterOutStatus=0;
    }
  }
  else if (topic == "mix") {
    if (mess == "1") {
      //Turn on mix
      Serial.println("Turn on mix");
      digitalWrite(RelayMix, HIGH);
      mixStatus=1;
    }
    else if (mess == "0") {
      //Turn off mix
      Serial.println("Turn off mix");
      digitalWrite(RelayMix, LOW);
      mixStatus=0;
    }
  }
}

// function that executes whenever data is requested from master
void requestEvent() { /*send string on request */
  char buffer[64];
  dataSend.toCharArray(buffer,64);
  Wire.write(buffer);
}

static int protothread1(struct pt *pt, int interval) {
  static unsigned long timestamp = 0;
  PT_BEGIN(pt);
  while(1) { // never stop 
    /* each time the function is called the second boolean
    *  argument "millis() - timestamp > interval" is re-evaluated
    *  and if false the function exits after that. */
    PT_WAIT_UNTIL(pt, millis() - timestamp > interval );
    timestamp = millis(); // take a new timestamp
    GetEC();
  }
  PT_END(pt);
}

void GetEC() {
  //*********Reading Temperature Of Solution *******************//
  sensors.requestTemperatures();// Send the command to get temperatures
  Temperature = sensors.getTempCByIndex(0); //Stores Value in Variable
  
  //************Estimates Resistance of Liquid ****************//
  digitalWrite(ECPower, HIGH);
  raw = analogRead(ECPin);
  raw = analogRead(ECPin); // This is not a mistake, First reading will be low beause if charged a capacitor
  digitalWrite(ECPower, LOW);

  //***************** Converts to EC **************************//
  Vdrop = (Vin * raw) / 1024.0;
  Rc = (Vdrop * R1) / (Vin - Vdrop);
  Rc = Rc - Ra; //acounting for Digital Pin Resitance
  EC = 1000 / (Rc * K);
  
  //*************Compensating For Temperaure********************//
  EC25  =  EC / (1 + TemperatureCoef * (Temperature - 21.0));
  ppm = (EC25) * (PPMconversion * 1000);
}
//************************** End OF EC Function ***************************//
