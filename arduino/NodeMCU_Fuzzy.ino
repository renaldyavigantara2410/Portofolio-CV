#include <WiFi.h>
#include <WiFiUdp.h>
#include <Wire.h>
#include <NTPClient.h>
#include <HTTPClient.h>
#include <RTClib.h>
#include <IOXhop_FirebaseESP32.h>
#include "SoftwareSerial.h"
#include <ArduinoJson.h>
#include <Arduino.h>

#define FIREBASE_HOST "https://siskupala-59c5f-default-rtdb.asia-southeast1.firebasedatabase.app/"
#define FIREBASE_AUTH "NfiJjdomBbZLWZnNDDtqdEIFUcFI3CtY0dSpcAPS"
#define WIFI_SSID "realme17"
#define WIFI_PASSWORD "budibudi"

RTC_DS3231 rtc;
const long utcOffsetInSeconds = 25200;
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "id.pool.ntp.org", utcOffsetInSeconds);

String arrData[3];

String hidup = "hidup";
String mati = "mati";
bool pompa1 = false;
bool pompa2 = false;
float fuzzy;
String keadaanph;
String keadaanturbidity;
String keadaansuhu;
String kualitasair;
SoftwareSerial Datasensor(32, 15);  // RX, TX
float ph_asam, ph_netral, ph_basa;
float ker_jernih, ker_standar, ker_keruh;
float suhu_dingin, suhu_normal, suhu_panas;
float z1, z2, z3, z4, z5, z6, z7, z8, z9, z10, z11, z12, z13, z14;
float Rule[15], minr[15];
float final_ph;    //deklarasi final_ph global
float final_ker;   //deklarasi final_ker global
float final_suhu;  //deklarasi final_suhu global
float kekeruhanValue;
float pHValue;
float suhuValue;

void setup() {
  Serial.begin(9600);  // Inisialisasi Serial Monitor ESP32
  Datasensor.begin(9600);

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

unsigned long previousMillisFirebase = 0;
const long intervalFirebase = 3600000;

void loop() {
  logikafuzzy();
  linguistik();
  static unsigned long previousMillis = 0;
  const long interval = 2000;
  static bool isFirstRun = true;  // Tambahkan variabel untuk menandai running pertama
  unsigned long currentMillis = millis();

  if (isFirstRun || (currentMillis - previousMillis >= interval)) {
    previousMillis = currentMillis;
    String data = "";
    while (Datasensor.available() > 0) {
      data += char(Datasensor.read());
    }
    data.trim();

    if (data != "") {
      int index = 0;
      for (int i = 0; i <= data.length(); i++) {
        char delimeter = '#';
        if (data[i] != delimeter)
          arrData[index] += data[i];
        else
          index++;
      }

      if (index == 2) {
        Serial.println("terima data serial");
        Serial.println("Nilai pH: " + arrData[0]);
        Serial.println("Nilai Kekeruhan: " + arrData[1]);
        Serial.println("Nilai Suhu Air: " + arrData[2]);
      }

      final_ph = arrData[0].toFloat();
      final_ker = arrData[1].toFloat();
      final_suhu = arrData[2].toFloat();
      arrData[0] = "";
      arrData[1] = "";
      arrData[2] = "";
    }
    Datasensor.println("Ya");
    previousMillis = currentMillis;
  }


  Firebase.set("realtime/pH_sensor", final_ph);
  Firebase.set("realtime/turbidity_sensor", final_ker);
  Firebase.set("realtime/water_temperature", final_suhu);

  if (currentMillis - previousMillisFirebase >= intervalFirebase) {
    previousMillisFirebase = currentMillis;
    kirimData();  // Panggil fungsi untuk mengirim data ke Firebase
  }
}

void logikafuzzy() {
  ph_asam = func_ph_asam();
  ph_netral = func_ph_netral();
  ph_basa = func_ph_basa();
  ker_jernih = func_ker_jernih();
  ker_standar = func_ker_standar();
  ker_keruh = func_ker_keruh();
  suhu_dingin = func_suhu_dingin();
  suhu_normal = func_suhu_normal();
  suhu_panas = func_suhu_panas();
  // Tampilkan hasil pH, suhu dan turbidity
  Serial.print("Turbidity: ");
  Serial.println(final_ker);
  //Output kekeruhan air dalam linguistik
  if (func_ker_keruh() > 0) {
    keadaanturbidity = "Keruh";
    Serial.println("Kekeruhan Air: Keruh");
  } else if (func_ker_standar() > 0) {
    keadaanturbidity = "Standar";
    Serial.println("Kekeruhan Air: Standar");
  } else if (func_ker_jernih() > 0) {
    keadaanturbidity = "Jernih";
    Serial.println("Kekeruhan Air: Jernih");
  }
  Serial.print("Nilai pH Air: ");
  Serial.println(final_ph);
  //Output keadaan pH air dalam linguistik
  if (func_ph_asam() > 0) {
    keadaanph = "Asam";
    Serial.println("Keadaan pH Air: Asam");
  } else if (func_ph_netral() > 0) {
    keadaanph = "Netral";
    Serial.println("Keadaan pH Air: Netral");
  } else if (func_ph_basa() > 0) {
    keadaanph = "Basa";
    Serial.println("Keadaan pH Air: Basa");
  }
  Serial.print("Nilai Suhu Air ");
  Serial.println(final_suhu);
  //Output keadaan pH air dalam linguistik
  if (func_suhu_dingin() > 0) {
    keadaansuhu = "Dingin";
    Serial.println("Keadaan Suhu Air: Dingin");
  } else if (func_suhu_normal() > 0) {
    keadaansuhu = "Normal";
    Serial.println("Keadaan Suhu Air: Normal");
  } else if (func_suhu_panas() > 0) {
    keadaansuhu = "Panas";
    Serial.println("Keadaan Suhu Air: Panas");
  }
  //Menghitung fungsi keanggotaan pH, kekeruhan dan suhu air
  minr[1] = minFunc(func_ph_asam(), func_ker_keruh(), func_suhu_dingin());
  Rule[1] = minr[1];
  z1 = (28 + (minr[1] * 1));
  minr[2] = minFunc(func_ph_asam(), func_ker_standar(), func_suhu_dingin());
  Rule[2] = minr[2];
  z2 = (28 + (minr[2] * 1));
  minr[3] = minFunc(func_ph_asam(), func_ker_keruh(), func_suhu_dingin());
  Rule[3] = minr[3];
  z3 = (28 + (minr[3] * 1));
  minr[4] = minFunc(func_ph_asam(), func_ker_jernih(), func_suhu_normal());
  Rule[4] = minr[4];
  z4 = (28 + (minr[4] * 1));
  minr[5] = minFunc(func_ph_asam(), func_ker_standar(), func_suhu_normal());
  Rule[5] = minr[5];
  z5 = (28 + (minr[5] * 1));
  minr[6] = minFunc(func_ph_asam(), func_ker_keruh(), func_suhu_normal());
  Rule[6] = minr[6];
  z6 = (28 + (minr[6] * 1));
  minr[7] = minFunc(func_ph_asam(), func_ker_jernih(), func_suhu_panas());
  Rule[7] = minr[7];
  z7 = (28 + (minr[7] * 1));
  minr[8] = minFunc(func_ph_netral(), func_ker_jernih(), func_suhu_normal());
  Rule[8] = minr[8];
  z8 = (29 - (minr[8] * 1));
  minr[9] = minFunc(func_ph_netral(), func_ker_standar(), func_suhu_normal());
  Rule[9] = minr[9];
  z9 = (29 - (minr[9] * 1));
  minr[10] = minFunc(func_ph_asam(), func_ker_standar(), func_suhu_panas());
  Rule[10] = minr[10];
  z10 = (28 + (minr[10] * 1));
  minr[11] = minFunc(func_ph_asam(), func_ker_keruh(), func_suhu_panas());
  Rule[11] = minr[11];
  z11 = (28 + (minr[11] * 1));
  minr[12] = minFunc(func_ph_netral(), func_ker_jernih(), func_suhu_panas());
  Rule[12] = minr[12];
  z12 = (29 - (minr[12] * 1));
  minr[13] = minFunc(func_ph_netral(), func_ker_standar(), func_suhu_panas());
  Rule[13] = minr[13];
  z13 = (29 - (minr[13] * 1));
  minr[14] = minFunc(func_ph_basa(), func_ker_standar(), func_suhu_normal());
  Rule[14] = minr[14];
  z14 = (29 - (minr[14] * 1));
  float apredikatz = 0;
  float a = 0;
  float defuzzy = 0;
  apredikatz = (Rule[1] * z1) + (Rule[2] * z2) + (Rule[3] * z3) + (Rule[4] * z4) + (Rule[5] * z5) + (Rule[6] * z6) + (Rule[7] * z7) + (Rule[8] * z8) + (Rule[9] * z9) + (Rule[10] * z10) + (Rule[11] * z11) + (Rule[12] * z12) + (Rule[13] * z13) + (Rule[14] * z14);
  a = Rule[14] + Rule[13] + Rule[12] + Rule[11] + Rule[10] + Rule[9] + Rule[8] + Rule[7] + Rule[6] + Rule[5] + Rule[4] + Rule[3] + Rule[2] + Rule[1];
  if (a != 0) {
    defuzzy = apredikatz / a;
  }
  fuzzy = defuzzy;
  Serial.print("Nilai Fuzzy: ");
  Serial.println(defuzzy);
  Firebase.set("kontrol/Nilai_fuzzy", defuzzy);
}

//membuat variabel linguistik hasil fuzzy
void linguistik() {
  float ph = final_ph;
  float turbidity = final_ker;
  float suhu = final_suhu;
  float kualitas_buruk = Rule[1] + Rule[2] + Rule[3] + Rule[4] + Rule[5] + Rule[6] + Rule[7] + Rule[10] + Rule[11];
  float kualitas_baik = Rule[8] + Rule[9] + Rule[12] + Rule[13] + Rule[14];
  if (kualitas_buruk >= kualitas_baik) {
    Serial.println("Kualitas Air: Buruk");
    kualitasair = "Buruk";
    Firebase.set("kontrol/kualitas_air", kualitasair);
    Firebase.set("realtime/kualitas_air", kualitasair);
    Firebase.set("kontrol/pompa1", hidup);
    pompa1 = true;
    Firebase.set("kontrol/pompa2", hidup);
    pompa2 = true;
    Serial.println("Pompa 1 dihidupkan");
    Serial.println("Pompa 2 dihidupkan");
  } else {
    Serial.println("Kualitas Air: Baik");
    kualitasair = "Baik";
    Firebase.set("kontrol/kualitas_air", kualitasair);
    Firebase.set("realtime/kualitas_air", kualitasair);
    Firebase.set("kontrol/pompa1", mati);
    pompa1 = false;
    Firebase.set("kontrol/pompa2", mati);
    pompa2 = false;
    Serial.println("Pompa 1 dimatikan");
    Serial.println("Pompa 2 dimatikan");
  }
}

float minFunc(float a, float b, float c) {
  if (a < b && a < c) {
    return a;
  } else if (b < c) {
    return b;
  } else {
    return c;
  }
}
// Fungsi keanggotaan pH Air
float func_ph_asam() {
  if (final_ph <= 6.5) {
    return 1;
  } else if (final_ph > 6.5 && final_ph < 7) {
    return (7 - final_ph) / (7 - 6.5);
  } else if (final_ph >= 7) {
    return 0;
  }
}
float func_ph_netral() {
  if (final_ph <= 6.5) {
    return 0;
  } else if (final_ph > 6.5 && final_ph < 7) {
    return (final_ph - 6.5) / (7 - 6.5);
  } else if (final_ph >= 7 && final_ph <= 8) {
    return 1;
  } else if (final_ph > 8 && final_ph < 8.5) {
    return (8.5 - final_ph) / (8.5 - 8);
  } else if (final_ph >= 8.5) {
    return 0;
  }
}
float func_ph_basa() {
  if (final_ph <= 8) {
    return 0;
  } else if (final_ph > 8 && final_ph < 8.5) {
    return (final_ph - 8) / (8.5 - 8);
  } else if (final_ph >= 8.5) {
    return 1;
  }
}
// Fungsi Keanggotaan Kekeruhan Air
float func_ker_jernih() {
  if (final_ker <= 15) {
    return 1;
  } else if (final_ker > 15 && final_ker < 17) {
    return (17 - final_ker) / (17 - 15);
  } else if (final_ker >= 17) {
    return 0;
  }
}
float func_ker_standar() {
  if (final_ker <= 15) {
    return 0;
  } else if (final_ker > 15 && final_ker < 17) {
    return (final_ker - 15) / (17 - 15);
  } else if (final_ker >= 17 && final_ker <= 28) {
    return 1;
  } else if (final_ker > 28 && final_ker < 30) {
    return (30 - final_ker) / (30 - 28);
  } else if (final_ker >= 30) {
    return 0;
  }
}
float func_ker_keruh() {
  if (final_ker <= 28) {
    return 0;
  } else if (final_ker > 28 && final_ker < 30) {
    return (final_ker - 28) / (30 - 28);
  } else if (final_ker >= 30) {
    return 1;
  }
}
// fungsi keanggotaan suhu air
float func_suhu_dingin() {
  if (final_suhu <= 22) {
    return 1;
  } else if (final_suhu > 22 && final_suhu < 24) {
    return (24 - final_suhu) / (24 - 22);
  } else if (final_suhu >= 24) {
    return 0;
  }
}
float func_suhu_normal() {
  if (final_suhu <= 22) {
    return 0;
  } else if (final_suhu > 22 && final_suhu < 24) {
    return (final_suhu - 22) / (24 - 22);
  } else if (final_suhu >= 24 && final_suhu <= 30) {
    return 1;
  } else if (final_suhu > 30 && final_suhu < 32) {
    return (32 - final_suhu) / (32 - 30);
  } else if (final_suhu >= 32) {
    return 0;
  }
}
float func_suhu_panas() {
  if (final_suhu <= 30) {
    return 0;
  } else if (final_suhu > 30 && final_suhu < 32) {
    return (final_suhu - 30) / (32 - 30);
  } else if (final_suhu >= 32) {
    return 1;
  }
}

void kirimData() {
  unsigned long epochTime = timeClient.getEpochTime();

  String formatjam = timeClient.getFormattedTime();

  struct tm *ptm = gmtime((time_t *)&epochTime);

  int Hari = ptm->tm_mday;

  int Bulan = ptm->tm_mon + 1;

  int Tahun = ptm->tm_year + 1900;

  String FormatWaktu = String(Hari) + "-" + String(Bulan) + "-" + String(Tahun);

  StaticJsonBuffer<1000> jsonBuffer;
  JsonObject &parsing = jsonBuffer.createObject();
  parsing["pH"] = final_ph;
  parsing["turbidty"] = final_ker;
  parsing["suhu"] = final_suhu;
  parsing["kualitas-air"] = kualitasair;
  parsing["Nilai_fuzzy"] = fuzzy;
  parsing["pompa1"] = pompa1 ? hidup : mati;
  parsing["pompa2"] = pompa2 ? hidup : mati;
  // parsing["keadaan_ph"] = keadaanph;      // Tambahkan keadaan pH
  // parsing["keadaan_suhu"] = keadaansuhu;  // Tambahkan keadaan suhu
  // parsing["keadaan_kekeruhan"] = keadaanturbidity;
  parsing["Tanggal"] = FormatWaktu;
  parsing["Waktu"] = formatjam;
  String name = Firebase.push("Data", parsing);
}