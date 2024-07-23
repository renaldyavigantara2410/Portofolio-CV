#include <Arduino.h>
#include <WiFi.h>
#include <ArduinoJson.h>
#include <NTPClient.h>
#include <HTTPClient.h>
#include <RTClib.h>
#include <IOXhop_FirebaseESP32.h>

#define FIREBASE_HOST "https://siskupala-59c5f-default-rtdb.asia-southeast1.firebasedatabase.app/"
#define FIREBASE_AUTH "NfiJjdomBbZLWZnNDDtqdEIFUcFI3CtY0dSpcAPS"
#define WIFI_SSID "realme17"
#define WIFI_PASSWORD "budibudi"

#define trigPin 13  // Pin Trig sensor ultrasonik
#define echoPin 12  // Pin Echo sensor ultrasonik
#define pompa1 2
#define pompa2 4

String hidup = "hidup";
String mati = "mati";

RTC_DS3231 rtc;
const long utcOffsetInSeconds = 25200;
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "id.pool.ntp.org", utcOffsetInSeconds);

const float initialWaterLevel = 25.0;
// Ketinggian air untuk memulai pembuangan (dalam cm) menyesuaikan kolam ikan
const float startDrainWaterLevel = 35.0;
// Ketinggian air setelah pembuangan (dalam cm) menyesuaikan kolam ikan
const float afterDrainWaterLevel = 25.0;

void setup() {
  Serial.begin(9600);  // Inisialisasi Serial Monitor ESP32
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  pinMode(pompa1, OUTPUT);
  pinMode(pompa2, OUTPUT);

  digitalWrite(pompa1, HIGH);
  digitalWrite(pompa2, HIGH);

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to ");
  Serial.print(WIFI_SSID);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(100);
  }
  timeClient.begin();
  timeClient.update();
  rtc.begin();
  rtc.adjust(DateTime(F(__DATE__), F(__TIME__)));
  rtc.adjust(DateTime(2021, 7, 17, timeClient.getHours(), timeClient.getMinutes(), timeClient.getSeconds()));

  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);
}

void loop() {
  String kualitasAir = Firebase.getString("kontrol/kualitas_air");
  if (kualitasAir == "Buruk") {
    // Hidupkan pompa 1             AIR 4 LITER
    Firebase.set("kontrol/pompa1", hidup);
    Firebase.set("realtime/pompa1", hidup);
    digitalWrite(pompa1, LOW);
    Serial.println("pompa 1 hidup");

    // Atur nilai jarak untuk pompa 1 (35 cm) 
    Firebase.set("realtime/ultrasonic_sensor_pompa1", 35);

    // Delay 1 menit (60.000 ms)
    delay(60000);

    // Matikan pompa 1
    Firebase.set("kontrol/pompa1", mati);
    Firebase.set("realtime/pompa1", mati);
    digitalWrite(pompa1, HIGH);
    Serial.println("pompa 1 Mati");

    // Hidupkan pompa 2 AIR 4,3 LITER
    Firebase.set("kontrol/pompa2", hidup);
    Firebase.set("realtime/pompa2", hidup);
    digitalWrite(pompa2, LOW);
    Serial.println("pompa 2 hidup");

    // Atur nilai jarak untuk pompa 2 (25 cm)
    Firebase.set("realtime/ultrasonic_sensor_pompa2", 25);

    // Delay 2 menit (120.000 ms)
    delay(120000);

    // Matikan pompa 2
    Firebase.set("kontrol/pompa2", mati);
    Firebase.set("realtime/pompa2", mati);
    digitalWrite(pompa2, HIGH);
    Serial.println("pompa 2 mati");

    //Delay untuk membaca kualitas air
    delay(60000);
  } else {
    // Jika kualitas air baik, matikan kedua pompa
    Firebase.set("kontrol/pompa1", mati);
    Firebase.set("realtime/pompa1", mati);
    digitalWrite(pompa1, HIGH);
    Serial.println("pompa 1 mati");
    Firebase.set("kontrol/pompa1", mati);
    Firebase.set("realtime/pompa2", mati);
    digitalWrite(pompa2, HIGH);
    Serial.println("pompa 2 mati");
  }
}
