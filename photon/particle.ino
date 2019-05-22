/*
Anemometr -> green(signal) D3, hnedy(V+) D7, cerny(V-)
BME280 -> D0 -> SDA ; D1 -> SCL
*/
#include "HttpClient.h"
#include "Adafruit_BME280.h"

SYSTEM_MODE(SEMI_AUTOMATIC);
STARTUP(WiFi.selectAntenna(ANT_EXTERNAL));

HttpClient http;
//TCPClient client;

//Headery pro HTTP Post request
http_header_t headers[] = {
    { "Content-Type", "application/json" },
    { "Accept" , "application/json" },
    { "Accept" , "*/*"},
    { NULL, NULL }
};
http_request_t request;
http_response_t response;

//Knihovny pro BME
Adafruit_BME280 bme;

    float vitr;
    float teplota;
    float tlak;
    float vlhkost;
    bool puls;
    bool stav;
    int counter;
    float batVal;

/* Debug mode? Pro povoleni je nutno propojit pin D5 a GND
    - vypne nizkou spotrebu
    - zapne rychly cyklus posilani dat
    - vhodne pro upravy skriptu za chodu
    - nebude vypinat oznamovaci diodu
*/
bool debug = false;
//Vyrobce anemometru udava konstatnu pro rychlost vetru 0.88 | Vypocet: Hz · 0.88
float konst = 0.88;
//Doba mereni rychlosti vetru v sekundach
int doba_mereni = 5;
//Useky mereni v ms, pokud by byl velký, impulzy splynou v jeden, doporucuje se polovina nejrychlejsiho mozneho impulzu
int citlivost_mereni = 5;
//Jak casto bude merit a odesilat data
int interval = 30; 
//Pin pro napajeni cidel. Sbernici Photonu dokaze prochazet 1000mA, cidla nezerou ani 10mA
int NAPAJENI = D7;
//Digitalni vstup pro vypocet impulzu anemometru
int ANEMOMETER = D3;
//Debug pin
int DEBUG = D5;
//Baterry sensor pin
int batPin = A5;

void setup() {
    Serial.begin(9600);
    
    pinMode(ANEMOMETER, INPUT_PULLUP);
    pinMode(DEBUG, INPUT_PULLUP);
    pinMode(NAPAJENI, OUTPUT);
    
    debug = !digitalRead(DEBUG);
    Serial.println(debug);
    if (debug){
    zapniwifi();
    };
    
    Serial.println();
    Serial.println("----------------------------------------------------");
    Serial.println("Inicializace Serioveho portu dokoncena..");

    pinMode(ANEMOMETER, INPUT_PULLDOWN);
    pinMode(NAPAJENI, OUTPUT);
    
    Serial.println("Inicializace pinu dokncena");
    
    delay(200);
    }

void loop() {
    if (!debug){
    RGB.control(true);
    RGB.brightness(0);
    }else{
    Serial.println("Debug mode on");
    
    WiFiSignal sig = WiFi.RSSI();
    Serial.printf("\nPripojeno k: %s\n", WiFi.SSID());
    Serial.printf("Sila wifi signalu: %.02f%%\n\n", sig.getStrength());
    };
    
    zmerBME();                                      //zmereni teploty, tlaku a vlhkosti
    zmerVITR();                                     //zmereni prumerne rychlosti vetru
    zmerBAT();                                      //zmeri hodnotu baterie
    
    if (!debug){
    zapniwifi();
    };
    
    delay(1000);
    send();                                         //odeslani na google sheets
    
    if (!debug){
    vypniwifi();
    RGB.control(false);
    deepsleep();
    };
}
void deepsleep(){ 
    Serial.print("DEEP SLEEP na: ");
    Serial.print(interval);
    Serial.println("s");
    Serial.println();
    Serial.println();
    
    delay(20);
    
    System.sleep(SLEEP_MODE_DEEP, interval);
}

void vypniwifi(){
    Serial.println("VYPINAM WIFI");
    
    WiFi.disconnect();
    WiFi.off();
    delay(200);
}

void zapniwifi(){
    Serial.println("ZAPINAM WIFI");
    
    WiFi.on();
    delay(500);
    WiFi.connect();
    
    while(!WiFi.ready())
    {
        Serial.println("pripojuji....");
    }
    Serial.println("Pripojeno!");
}

void zmerBME(){
    digitalWrite(NAPAJENI, HIGH);                   //zapinam napajeni pro cidl
    delay(100);
    bme.begin(0x76);                                //zapinam BME cidlo
    delay(100);
    
    Serial.println();
    Serial.println("Provadim mereni z BME CIDLA");
    
    teplota = bme.readTemperature();
    tlak = bme.readPressure() / 100.0F;
    vlhkost = bme.readHumidity();
    
    digitalWrite(NAPAJENI, LOW);
    
    Serial.print("Teplota = \t");
    Serial.print(teplota);
    Serial.println(" *C");
    
    Serial.print("Tlak = \t\t");
    Serial.print(tlak);
    Serial.println(" hPa");

    Serial.print("Vlhkost = \t");
    Serial.print(vlhkost);
    Serial.println(" %");
}

void zmerVITR(){
    digitalWrite(NAPAJENI, HIGH);
    delay(20);
    
    Serial.println();
    Serial.print("Provadim mereni z anemometru, bude to trvat: ");
    Serial.print(doba_mereni);
    Serial.println("sec");
    
    int pocet_impulzu = 0;
    for (int i=0; i<doba_mereni * 1000 / citlivost_mereni; i++){
        stav = digitalRead(ANEMOMETER);
        if ((stav) && !(puls)){
            puls = true;
        }else if(!(stav) && (puls)){
            pocet_impulzu++;
            puls = false;
        }
        delay(citlivost_mereni);
    }
    
    vitr = ((pocet_impulzu / 8.0) / doba_mereni) * konst;
    
    Serial.print("Rychlost vetru = ");
    Serial.print(vitr);
    Serial.println(" m/s");
    
    digitalWrite(NAPAJENI, LOW);
}
void zmerBAT(){
    int maxVal = 2910; //odpovida 4.20 V
    int minVal = 2350; //odpovida 3.00 V
    int rozsah = maxVal - minVal; // cca 380
    float sensorValue = analogRead(batPin);
    
    if (sensorValue >= maxVal){
         batVal = 100;
    }else if(sensorValue <= minVal){
         batVal = 0;    
    }else{
    batVal = ((sensorValue-minVal)*100)/rozsah;
    }
}

/*TODO:
    · HASH requestu (asi na zaklade hesla a casu)
*/
// poslani na sheet
void send(){
    String command = "{\"temp\":\""+String(teplota)+"\",\"hum\":\""+String(vlhkost)+"\",\"press\":\""+String(tlak)+"\",\"ws\":\""+String(vitr)+"\",\"power\":\""+String(batVal)+"\"}";
    //String command = "temp="+String(teplota)+"&hum="+String(vlhkost)+"&press="+String(tlak)+"&ws="+String(vitr);
    //String command = "temp=23.700001&hum=48.339844&press=982.538879&ws=0.000000";

    Serial.println();
    Serial.println("SheetConnect>\tOdesilani na sheet.");
    
    request.hostname = "YOUR HOSTNAME HERE";
    request.port = 80;
    request.path = "YOUR sheetwrite.php SCRIPT HERE";
    request.body = command;

    http.get(request, response, headers);
    
    Serial.print("Request status>\t");
    Serial.println(response.status);

    Serial.print("Zpetna vazba>\t");
    Serial.println(response.body);
    Serial.println();
}