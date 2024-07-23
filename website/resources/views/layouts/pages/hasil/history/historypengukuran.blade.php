@extends('layouts.master')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10">
            Data Pengukuran
        </h2>
        <div>
            <div class="mt-5">
                <table id="dataTableHistory" class="display row-border cell-border stripe nowrap" style="width: 100%">
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-app.js";
        import {
            getDatabase,
            ref,
            onValue
        } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{ config('firebase.projects.app.apiKey') }}",
            authDomain: "{{ config('firebase.projects.app.authDomain') }}",
            databaseURL: "{{ config('firebase.projects.app.database.url') }}",
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase();
        const starCountRefSensor = ref(db, 'records');

        let table;

        onValue(starCountRefSensor, (snapshot) => {
            let data = snapshot.val();
            console.log("Data dari Firebase:", data);

            if (!table) {
                table = initializeDataTable();
            }
            // Bersihkan data sebelumnya dari tabel
            table.clear();

            // Ubah data menjadi array untuk diurutkan
            const dataArray = [];
            for (const key in data) {
                if (Object.hasOwnProperty.call(data, key)) {
                    const row = data[key];
                    row.key = key; // Simpan key sebagai bagian dari data
                    dataArray.push(row);
                }
            }
            // Urutkan dataArray berdasarkan timestamp secara menurun (descending)
            dataArray.sort((a, b) => {
                if (a.timestamp && b.timestamp) {
                    const dateA = new Date(
                        parseInt(a.timestamp.split(" ")[3]), // Tahun
                        parseInt(a.timestamp.split(" ")[1]) -
                        1, // Bulan (Kurangi 1 karena bulan dimulai dari 0)
                        parseInt(a.timestamp.split(" ")[2]), // Tanggal
                        parseInt(a.timestamp.split(" ")[0].split(":")[0]), // Jam
                        parseInt(a.timestamp.split(" ")[0].split(":")[1]), // Menit
                        parseInt(a.timestamp.split(" ")[0].split(":")[2]) // Detik
                    );
                    const dateB = new Date(
                        parseInt(b.timestamp.split(" ")[3]), // Tahun
                        parseInt(b.timestamp.split(" ")[1]) -
                        1, // Bulan (Kurangi 1 karena bulan dimulai dari 0)
                        parseInt(b.timestamp.split(" ")[2]), // Tanggal
                        parseInt(b.timestamp.split(" ")[0].split(":")[0]), // Jam
                        parseInt(b.timestamp.split(" ")[0].split(":")[1]), // Menit
                        parseInt(b.timestamp.split(" ")[0].split(":")[2]) // Detik
                    );
                    return dateB - dateA; // Urutkan secara menurun
                } else {
                    // Handle jika a.timestamp atau b.timestamp adalah undefined
                    return 0; // Atau nilai lain sesuai dengan kebutuhan Anda
                }
            });


            // Tambahkan data ke dalam tabel sesuai urutan waktu
            dataArray.forEach((row) => {
                const turbidity = row.turbidity_sensor !== undefined ? row.turbidity_sensor : "";
                const ph = row.ph_sensor !== undefined ? row.ph_sensor : "";
                const ultrasonic_2 = row.ultrasonic_sensor_1 !== undefined ? row.ultrasonic_sensor_1 : "";
                const ultrasonic_1 = row.ultrasonic_sensor !== undefined ? row.ultrasonic_sensor : "";
                const statusPompa = row.status_pompa !== undefined ? row.status_pompa : "";
                const keterangan = row.keterangan !== undefined ? row.keterangan : "";
                const timestamp = row.timestamp ? formatDateTime(row.timestamp) : "";

                table.row.add([
                    turbidity,
                    ph,
                    ultrasonic_2,
                    ultrasonic_1,
                    statusPompa,
                    keterangan,
                    timestamp
                ]).draw(false); // Gunakan draw(false) untuk menambahkan data tanpa merender ulang tabel
            });
        });

        function formatDateTime(dateTime) {
            var date = new Date(dateTime);
            var timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            var formattedTime = date.toLocaleTimeString('en-ID', timeOptions);
            var dateOptions = {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric'
            };
            var formattedDate = date.toLocaleDateString('en-US', dateOptions);
            return formattedTime + '  ' + formattedDate;
        }

        function initializeDataTable() {
            return $('#dataTableHistory').DataTable({
                columns: [{
                        title: "Tingkat Kekeruhan"
                    },
                    {
                        title: "Tingkat Keasaman"
                    },
                    {
                        title: "Jarak Air 1"
                    },
                    {
                        title: "Jarak Air 2"
                    },
                    {
                        title: "Status Pompa"
                    },
                    {
                        title: "Keterangan"
                    },
                    {
                        title: "Waktu"
                    }
                ],
                responsive: true,
                processing: true,
                retrieve: true,
            });
        }
    </script>
@endpush
