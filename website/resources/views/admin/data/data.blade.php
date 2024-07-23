@extends('layouts.master')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Riwayat Kualitas Air
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12">
            <table id="dataTableHistory" class="display nowrap" style="width:100%">
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{ config('firebase.projects.app.apiKey') }}",
            authDomain: "{{ config('firebase.projects.app.authDomain') }}",
            databaseURL: "{{ config('firebase.projects.app.database.url') }}",
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase();
        const refHistory = ref(db, 'Data');

        onValue(refHistory, (snapshot) => {
            const data = snapshot.val();
            // console.log(data);
            dataTableHistory(data);
        });

        // Remove or define refPakan if necessary
        // const refPakan = ref(db, 'pakan');

        function dataTableHistory(data) {
            let tableref = new DataTable('#dataTableHistory', {
                data: Object.values(data),
                columns: [
                    { title: "Tanggal", data: "Tanggal" },
                    { title: "Waktu", data: "Waktu" },
                    { title: "Kualitas Air", data: "kualitas-air" },
                    { title: "Turbidity", data: "turbidty" },
                    { title: "pH", data: "pH" },
                    { title: "Suhu Air", data: "suhu" },
                    { title: "Pompa1", data: "pompa1" },
                    { title: "Pompa2", data: "pompa2" },
                ],
                responsive: true
            });
        }
    </script>
@endpush
