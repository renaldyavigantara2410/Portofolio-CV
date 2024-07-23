#include <WiFi.h>
#include <WiFiUdp.h>
#include <NTPClient.h>
#include <RTClib.h>
#include <ESP32Servo.h>
#include <SPI.h>
#include <Wire.h>
#include <IOXhop_FirebaseESP32.h>
#include <LiquidCrystal_I2C.h>

#define FIREBASE_HOST "https://siskupala-59c5f-default-rtdb.asia-southeast1.firebasedatabase.app/"
#define FIREBASE_AUTH "NfiJjdomBbZLWZnNDDtqdEIFUcFI3CtY0dSpcAPS"
#define WIFI_SSID "realme17"
#define WIFI_PASSWORD "budibudi"

static const int servoPin = 18;
LiquidCrystal_I2C lcd(0x27, 20, 4);
Servo servo1;

RTC_DS3231 rtc;
const long utcOffsetInSeconds = 25200;  // Offset untuk WIB (UTC+7)
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "id.pool.ntp.org", utcOffsetInSeconds);

void setup() {
  Serial.begin(115200);

  // LCD initialization
  lcd.init();
  lcd.backlight();
  delay(2000); // Give time for the LCD to initialize

  // Servo initialization
  servo1.attach(servoPin);
  servo1.write(90);

  // WiFi connection
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to ");
  Serial.print(WIFI_SSID);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(100);
  }
  Serial.println("\nWiFi connected.");

  // Time client initialization
  timeClient.begin();
  timeClient.update();
  rtc.begin();
  rtc.adjust(DateTime(F(_DATE), F(TIME_)));
  rtc.adjust(DateTime(2021, 10, 29, timeClient.getHours(), timeClient.getMinutes(), timeClient.getSeconds()));

  // Firebase initialization
  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);
  Serial.println("Firebase connected.");

  // Welcome message on LCD
  lcd.setCursor(4, 0);
  lcd.print("Welcome");
  lcd.setCursor(3, 1);
  lcd.print("SISKUPALA");
  delay(2000); // Longer delay to ensure the welcome message is seen
  lcd.clear();
}

void logikapakanpagi() {
  timeClient.update();
  String formattedTime = timeClient.getFormattedTime();
  Serial.print("Current time: ");
  Serial.println(formattedTime);

  String jadwal1 = Firebase.getString("jadwalPakan/jadwal1");
  int jam = jadwal1.substring(0, 2).toInt();
  int menit = jadwal1.substring(3, 5).toInt();
  String jumlah = Firebase.getString("jadwalPakan/putaranServo1");

  // Debugging output
  Serial.print("Pagi schedule: ");
  Serial.print(jam);
  Serial.print(":");
  Serial.println(menit);
  Serial.print("Putaran Servo (Pagi): ");
  Serial.println(jumlah);

  // Handle default value if jumlah is zero
  if (jumlah.toInt() == 0) {
    Serial.println("Putaran Servo (Pagi) is 0, fetching default value from Firebase.");
    jumlah = Firebase.getString("jadwalPakan/putaranServo1");
    Serial.print("Fetched default Putaran Servo (Pagi): ");
    Serial.println(jumlah);
  }

  // Periksa apakah sudah memberi makan pada waktu ini
  if (timeClient.getHours() == jam && timeClient.getMinutes() == menit && Firebase.getInt("jadwalPakan/Pagi/Sudah") == 0) {
    Serial.println("Pagi time matched. Feeding now...");
    Serial.print("Calling pakan with putaranServo: ");
    Serial.println(jumlah);
    Firebase.set("jadwalPakan/Pagi/Sudah", 1);
    Firebase.set("jadwalPakan/Status", 1);
    pakan(jumlah.toInt(), "Pagi");
  } else {
    Serial.println("Pagi time not matched or already fed.");
  }
}

void logikapakansore() {
  timeClient.update();
  String formattedTime = timeClient.getFormattedTime();
  Serial.print("Current time: ");
  Serial.println(formattedTime);

  String jadwal2 = Firebase.getString("jadwalPakan/jadwal2");
  int jam = jadwal2.substring(0, 2).toInt();
  int menit = jadwal2.substring(3, 5).toInt();
  String jumlah = Firebase.getString("jadwalPakan/putaranServo2");

  // Debugging output
  Serial.print("Sore schedule: ");
  Serial.print(jam);
  Serial.print(":");
  Serial.println(menit);
  Serial.print("Putaran Servo (Sore): ");
  Serial.println(jumlah);

  // Handle default value if jumlah is zero
  if (jumlah.toInt() == 0) {
    Serial.println("Putaran Servo (Sore) is 0, fetching default value from Firebase.");
    jumlah = Firebase.getString("jadwalPakan/putaranServo2");
    Serial.print("Fetched default Putaran Servo (Sore): ");
    Serial.println(jumlah);
  }

  // Periksa apakah sudah memberi makan pada waktu ini
  if (timeClient.getHours() == jam && timeClient.getMinutes() == menit && Firebase.getInt("jadwalPakan/Sore/Sudah") == 0) {
    Serial.println("Sore time matched. Feeding now...");
    Serial.print("Calling pakan with putaranServo: ");
    Serial.println(jumlah);
    Firebase.set("jadwalPakan/Sore/Sudah", 1);
    Firebase.set("jadwalPakan/Status", 1);
    pakan(jumlah.toInt(), "Sore");
  } else {
    Serial.println("Sore time not matched or already fed.");
  }
}

void pakan(int jumlah, String waktu) {
  // Serial.println("Entering pakan function");
  // Serial.print("Jumlah putaran: ");
  // Serial.println(jumlah);

  if (jumlah == 0) {
    // Serial.println("Jumlah putaran is 0, skipping feeding.");
    return;
  }

  for (int i = 1; i <= jumlah; i++) {
    // Serial.print("Saatnya Pakan Diberikan ");
    // Serial.print(waktu);
    // // Serial.print(" cycle: ");
    // Serial.println(i);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Pakan: ON");
    Serial.println("Pakan: ON");
    delay(1000);
    servo1.write(180);
    // Serial.println("Servo set to 180");
    delay(500);
    servo1.write(0);
    // Serial.println("Servo set to 90");
    delay(1000);
    lcd.clear();
    Firebase.set("jadwalPakan/" + waktu + "/statusputar", i);
  }
}

void logikapakanreset() {
  timeClient.update();
  String formattedTime = timeClient.getFormattedTime();
  Serial.print("Waktu saat ini: ");
  Serial.println(formattedTime);

  if (timeClient.getHours() == 0 && timeClient.getMinutes() == 0) {
    Firebase.set("KontrolPakan/Pagi/Sudah", 0);
    Firebase.set("KontrolPakan/Pagi/statusputar", 0);
    Firebase.set("KontrolPakan/Sore/Sudah", 0);
    Firebase.set("KontrolPakan/Sore/statusputar", 0);
    Firebase.set("KontrolPakan/Status", 0);
    Serial.println("Resetting feeding status.");
  }
}

void loop() {
  logikapakanpagi();
  logikapakansore();
  logikapakanreset();
  
  // Display the current time on the LCD
  timeClient.update();
  String formattedTime = timeClient.getFormattedTime();
  lcd.setCursor(0, 0);
  lcd.print("Time: ");
  lcd.print(formattedTime);
  
  delay(1000);  // Add a delay to avoid checking conditions too frequently
  // Serial.println("Selesai satu siklus loop.");
}