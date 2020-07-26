#include <Wire.h>
#include <DHT.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <pt.h>
#include <string.h>

//id
const int id = 4; //id của thiết bị
//id 4 -> addr 4, id 5 -> addr 5

//Sensor
const int TRIG_PIN = 13; //Pin đọc cảm biến siêu âm
const int ECHO_PIN = 12; //Pin đọc cảm biến siêu âm
const int DHTPIN = 2; //Đọc dữ liệu từ DHT11 ở chân 2 trên mạch Arduino
const int DHTTYPE = DHT11; //Khai báo loại cảm biến, có 2 loại là DHT11 và DHT22
const int Light =  A2; //Pin đọc cảm biến ánh sáng
DHT dht(DHTPIN, DHTTYPE); //Cảm biến nhiệt độ và độ ẩm

//Relay
const int RelayPpm = 7; //Relay pha chế
const int RelayWaterIn = 8; //Relay bơm nước vào thùng
const int RelayMix = 9; //Relay motor quay trộn dinh dưỡng
const int RelayWaterOut = 10;//Relay bơm nước ra khỏi thùng
const int RelayPump = 11; //Relay bơm nước lên giàn thủy canh

//i2c
const int addr = 4; //Địa chỉ kết nối i2c
bool stringComplete = false; //Biến kiểm tra chuỗi toàn vẹn
String inputString; //Biến lưu chuỗi truyền từ i2c
char inputString2;
//String inputString = "4=pump=1";

//Thread
static struct pt pt1; //Khai báo thread

//EC
const int ONE_WIRE_BUS = 5; //Pin đọc cảm biến nhiệt độ nước
int R1 = 850; //Biến có thay đổi nên không dùng const
const int Ra = 25; //Điện trở của chân cấp nguồn
const int ECPin = A0; //EC pin
const int ECGround = A1; //EC nối GND
const int ECPower = A3; //EC năng lượng
//*********** Converting to ppm [Learn to use EC it is much better**************//
// Hana      [USA]        PPMconverion:  0.5
// Eutech    [EU]          PPMconversion:  0.64
// Tranchen  [Australia]  PPMconversion:  0.7
// Why didnt anyone standardise this?
const float PPMconversion = 0.7;
//*************Compensating for temperature ************************************//
//The value below will change depending on what chemical solution we are measuring
//0.019 is generaly considered the standard for plant nutrients [google "Temperature compensation EC" for more info
const float TemperatureCoef = 0.019; //this changes depending on what chemical we are measuring
//********************** Cell Constant For Ec Measurements *********************//
//Mine was around 2.9 with plugs being a standard size they should all be around the same
//But If you get bad readings you can use the calibration script and fluid to get a better estimate for K
const float K = 2.7;
//************ Temp Probe Related *********************************************//
OneWire oneWire(ONE_WIRE_BUS);// Setup a oneWire instance to communicate with any OneWire devices
DallasTemperature sensors(&oneWire);// Pass our oneWire reference to Dallas Temperature.

//Temp
String dataSend = ""; //Dữ liệu truyền qua i2c từ Arduino
String dataSend1 = ""; //Dữ liệu truyền qua i2c tạm từ Arduino

int pumpStatus = 0; //Trạng thái relay bơm
int waterInStatus = 0; //Trạng thái relay nước vào
int waterOutStatus = 0; //Trạng thái relay nước ra
int mixStatus = 0; //Trạng thái relay trộn

float Temperature = 0; //Biến lưu nhiệt độ nước
float EC = 0; //Biến lưu độ dẫn điện
float EC25 = 0; //Biến chuyển đổi độ dẫn điện và nhiệt độ nước
int ppm = 0; //Biến lưu dinh dưỡng

int checkppm = 0; //Biến kiểm tra dinh dưỡng để thêm
float raw = 0; //Đọc analog từ ECPin
float Vin = 5;
float Vdrop = 0;
float Rc = 0;

float distance; //Khoảng cách theo mức thùng (%) Min 3cm Max 19cm
float distance1; //Khoảng cách theo cảm biến siêu âm (cm)
float distancelast; //Làm tròn số thập phân
int distancesend; //Làm tròn số nguyên để gửi

void setup() {
  //Khai báo Serial
  Serial.begin(9600);

  //Khai báo i2c
  Wire.begin(addr);
  Wire.onReceive(receiveEvent);
  Wire.onRequest(requestEvent);
  inputString.reserve(200);

  //Khai báo thread
  PT_INIT(&pt1);  //thread 1

  //Khai báo PIN 7->13
  pinMode(RelayPpm, OUTPUT);
  pinMode(RelayWaterIn, OUTPUT);
  pinMode(RelayMix, OUTPUT);
  pinMode(RelayWaterOut, OUTPUT);
  pinMode(RelayPump, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  pinMode(TRIG_PIN, OUTPUT);

  //Khai báo EC
  pinMode(ECPin, INPUT);
  pinMode(ECPower, OUTPUT); //Setting pin for sourcing current
  pinMode(ECGround, OUTPUT); //setting pin for sinking current
  digitalWrite(ECGround, LOW); //We can leave the ground connected permanantly
  pinMode(Light, INPUT);
  delay(100);// gives sensor time to settle
  sensors.begin();
  delay(100);
  R1 = (R1 + Ra); // Taking into acount Powering Pin Resitance
  
  //Khởi động cảm biến DHT11
  dht.begin(); 
}
 
void loop() {
  if(checkppm == 1){
    digitalWrite(RelayPpm, HIGH);
    delay(1000);
    digitalWrite(RelayPpm, LOW);
    checkppm = 0;
  }
  int hum = dht.readHumidity();    //Đọc độ ẩm
  int temp = dht.readTemperature(); //Đọc nhiệt độ
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
  if(distancelast > 100){
    distancelast = 100;
  }
  distancesend = distancelast;
  sensors.requestTemperatures();// Send the command to get temperatures
  Temperature = sensors.getTempCByIndex(0); //Stores Value in Variable
  /////////////////////////////EC PPM
  //Calls Code to Go into GetEC() Loop [Below Main Loop] dont call this more than 1/5 hhz [once every five seconds] or you will polarise the water
  //GetEC();
  protothread1(&pt1, 5000);
  // id, device_id, temperature, humidity, light, EC, PPM, water, pump, water_in, water_out, mix
    //  temp = 25 + rand() % 2 - 1;
    //  hum = 50 + rand() % 2 - 1;
    //  lig = 1100 + rand() % 10 - 5;
    //  EC25 = 1.42 + (rand() % 2 - 1)/10;
    //  ppm = 1000 + rand() % 20 - 10;
    //  distancesend = 64 + rand() % 2 - 1;
  dataSend1 = (String)id+"="+(String)temp+"="+(String)hum+"="+(String)lig+"="+(String)EC25+"="+(String)ppm+"="+(String)distancesend+"="+(String)pumpStatus+(String)waterInStatus+(String)waterOutStatus+(String)mixStatus;
//  dataSend = "6=26=42=1258=1.40=1253=56=1000";
  if(dataSend1.length() <= 32){
    dataSend = dataSend1;
  }
  else {
    dataSend = "";
  }
  Serial.println(dataSend);
  delay(3000);
}
 
void receiveEvent(int howMany) {
  while(Wire.available()>0)
  {
    char inChar = Wire.read(); 
    if (inChar == '\n') {
      stringComplete = true;
    }
    else{
      inputString += String(inChar);
    }
  }
  //Cắt chuỗi
  if (inputString.equals("4=pump=1")){
    inputString = "4=pump=1";
  }
  else if (inputString.equals("4=pump=0")){
    inputString = "4=pump=0";
  }
  else if (inputString.equals("4=waterIn=1")){
    inputString = "4=waterIn=1";
  }
  else if (inputString.equals("4=waterIn=0")){
    inputString = "4=waterIn=0";
  }
  else if (inputString.equals("4=waterOut=1")){
    inputString = "4=waterOut=1";
  }
  else if (inputString.equals("4=waterOut=0")){
    inputString = "4=waterOut=0";
  }
  else if (inputString.equals("4=ppm=1")){
    inputString = "4=ppm=1";
  }
  else if (inputString.equals("4=ppm=0")){
    inputString = "4=ppm=0";
  }
  else if (inputString.equals("4=mix=1")){
    inputString = "4=mix=1";
  }
  else if (inputString.equals("4=mix=0")){
    inputString = "4=mix=0";
  }

  char str[50];
  String tempArr[10];
  int i = 0;
  char * pch;
  strcpy(str, inputString.c_str());
  Serial.println(str);
  pch = strtok (str, "=");
  
  while (pch != NULL)
  {
    tempArr[i] = pch;
    pch = strtok (NULL, "=");
    i++;
  }
  //Kiểm tra id thiết bị
  if (atoi(tempArr[0].c_str()) == id){
    Serial.println("My id");
    if (tempArr[1].equals("pump")){
      if (tempArr[2].equals("1")){
        //Turn on pump
        Serial.println("Turn on pump");
        digitalWrite(RelayPump, HIGH);
        pumpStatus=1;
      }
      else if (tempArr[2].equals("0")){
        //Turn off pump
        Serial.println("Turn off pump");
        digitalWrite(RelayPump, LOW);
        pumpStatus=0;
      }
    }
    else if (tempArr[1].equals("ppm")) {
      if (tempArr[2].equals("1")) {
        //Turn on ppm
        Serial.println("Turn on ppm");
        checkppm = 1;
      }
      else if (tempArr[2].equals("0")) {
        //Turn off ppm
        Serial.println("Turn off ppm");
        digitalWrite(RelayPpm, LOW);
      }
    }
    else if (tempArr[1].equals("waterIn")) {
      if (tempArr[2].equals("1")) {
        //Turn on waterIn RelayWaterIn
        Serial.println("Turn on waterIn");
        digitalWrite(RelayWaterIn, HIGH);
        waterInStatus=1;
      }
      else if (tempArr[2].equals("0")) {
        //Turn off waterIn
        Serial.println("Turn off waterIn");
        digitalWrite(RelayWaterIn, LOW);
        waterInStatus=0;
      }
    }
    else if (tempArr[1].equals("waterOut")) {
      if (tempArr[2].equals("1")) {
        //Turn on waterOut
        Serial.println("Turn on waterOut");
        digitalWrite(RelayWaterOut, HIGH);
        waterOutStatus=1;
      }
      else if (tempArr[2].equals("0")) {
        //Turn off waterOut
        Serial.println("Turn off waterOut");
        digitalWrite(RelayWaterOut, LOW);
        waterOutStatus=0;
      }
    }
    else if (tempArr[1].equals("mix")) {
      if (tempArr[2].equals("1")) {
        //Turn on mix
        Serial.println("Turn on mix");
        digitalWrite(RelayMix, HIGH);
        mixStatus=1;
      }
      else if (tempArr[2].equals("0")) {
        //Turn off mix
        Serial.println("Turn off mix");
        digitalWrite(RelayMix, LOW);
        mixStatus=0;
      }
    }
  }
  inputString = "";
  stringComplete = false;
}

void requestEvent() { /*send string on request */
  char buffer[32];
  dataSend.toCharArray(buffer,32);
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
