#include <SoftwareSerial.h>
#include <OneWire.h>
#include <DallasTemperature.h>

SoftwareSerial Datasensor(2, 3);  // RX, TX

const int adc_ph4 = 980;
const int adc_ph9 = 740;

float slope = (9.0 - 4.0) / (adc_ph9 - adc_ph4);
float intercept = 4.0 - slope * adc_ph4;

unsigned long int avgval;
int buffer_arr[10], temp;
float sensorPin = A1;
float ntu;
const int oneWireBusPin = 5;

String dataRead;
float turbidity, final_ph, celsius;  // final_ph dideklarasikan sebagai variabel global

OneWire oneWire(oneWireBusPin);
DallasTemperature sensors(&oneWire);

void setup() {
  Serial.begin(9600);
  sensors.begin();
  Datasensor.begin(9600);
}

void loop() {
  // int adcValue = analogRead(A0);
  // Serial.print("ADC Value: ");
  // Serial.println(adcValue);
  // delay(1000);

  pH();         // Memanggil fungsi pH
  Kekeruhan();  // Memanggil fungsi Kekeruhan
  BacaSuhu();

  String espRequestData = "";
  while (Datasensor.available() > 0) {
    espRequestData += char(Datasensor.read());
  }
  espRequestData.trim();
  if (espRequestData == "Ya") {
    kirimData();
  }
  delay(2000);
}

void pH() {
  for (int i = 0; i < 5; i++) {
    buffer_arr[i] = analogRead(A0);
    delay(10);
  }
  for (int i = 0; i < 4; i++) {
    for (int j = i + 1; j < 5; j++) {
      if (buffer_arr[i] > buffer_arr[j]) {
        temp = buffer_arr[i];
        buffer_arr[i] = buffer_arr[j];
        buffer_arr[j] = temp;
      }
    }
  }
  avgval = 0;
  for (int i = 1; i < 4; i++)  // Mengambil rata-rata dari nilai tengah
    avgval += buffer_arr[i];

  float adcValue = (float)avgval / 3;
  final_ph = slope * adcValue + intercept;  // Menyimpan nilai ke variabel global final_ph

  Serial.print("pH Val: ");
  Serial.println(final_ph, 2);
}

void Kekeruhan() {
  float sensorValue = analogRead(sensorPin);
  float voltage = sensorValue * (5.0 / 1024.0);
  turbidity = 1.65 * voltage + 25.45;
  Serial.print("Nilai Turbidity: ");
  Serial.println(turbidity);
}

void BacaSuhu() {
  sensors.requestTemperatures();
  celsius = sensors.getTempCByIndex(0);
  Serial.print("Suhu Celsius: ");
  Serial.print(celsius);
  Serial.println("°C");
}

void kirimData() {
  dataRead = String(final_ph) + '#' + String(turbidity) + '#' + String(celsius);
  Datasensor.println(dataRead);
  Serial.println(dataRead);
}
