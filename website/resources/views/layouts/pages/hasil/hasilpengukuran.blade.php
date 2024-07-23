@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-9">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 mt-8">
                        <div class="intro-y flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Hasil Pengukuran
                            </h2>
                        </div>
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <div class="col-span-12 lg:col-span-4 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="flex">
                                            <img alt="" class="w-8" src="{{ asset('images/star.svg') }}">
                                        </div>
                                        <div class="text-base text-slate-500 mt-6">Tingkat Kekeruhan</div>
                                        <div id="turbidity_sensor" class="text-lg lg:text-3xl font-semibold leading-8 mt-3">
                                            0
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="flex">
                                            <img alt="" class="w-8" src="{{ asset('images/drop.svg') }}">
                                        </div>
                                        <div class="text-base text-slate-500 mt-6">Tingkat Keasaman</div>
                                        <div id="ph_sensor" class="text-lg lg:text-3xl font-semibold leading-8 mt-3">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="flex">
                                            <img alt="" class="w-8" src="{{ asset('images/rec.svg') }}">
                                        </div>
                                        <div class="text-base text-slate-500 mt-6">Jarak Air 1</div>
                                        <div id="ultrasonic_sensor_2"
                                            class="text-lg lg:text-3xl font-semibold leading-8 mt-3">0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-6 mt-20">
                            <div class="col-span-12 lg:col-span-4 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="flex">
                                            <img alt="" class="w-8" src="{{ asset('images/rec.svg') }}">
                                        </div>
                                        <div class="text-base text-slate-500 mt-6">Jarak Air 2</div>
                                        <div id="ultrasonic_sensor_1"
                                            class="text-lg lg:text-3xl font-semibold leading-8 mt-3">0
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-8 intro-y">
                                <div class="box p-5 mr-5">
                                    <div class="flex">
                                        <img alt="" class="w-8" src="{{ asset('images/info.svg') }}">
                                    </div>
                                    <div class="flex items-center">
                                        <div class="text-base mt-6 text-lg lg:text-2x1 font-semibold">KETERANGAN</div>
                                    </div>
                                    <div class="mt-5 flex flex-row content-center font-bold items-center">Status Air : <span
                                            id="information"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-3">
                <div class="grid grid-cols-6 gap-2">
                    <div class="col-span-12 mt-8">
                        <div class="intro-y flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Status Pompa
                            </h2>
                        </div>
                        <div class="grid grid-cols-12 md:grid-rows-12 gap-2 mt-5">
                            <div class="col-span-12 md:row-span-12 intro-y">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="text-base text-slate-500">Status Pompa 1</div>
                                        <div class="flex mt-6">
                                            <div class="flex flex-row">
                                                <img alt="" class="w-8" src="{{ asset('images/power.svg') }}">
                                                <div id="pompa_1" class="text-3xl font-medium leading-8 ml-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 md:row-span-12 intro-y mt-6">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="text-base text-slate-500">Status Pompa 2</div>
                                        <div class="flex mt-6">
                                            <div class="flex flex-row">
                                                <img alt="" class="w-8" src="{{ asset('images/power.svg') }}">
                                                <div id="pompa_2" class="text-3xl font-medium leading-8 ml-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 md:row-span-12 intro-y mt-6">
                                <div class="report-box zoom-in">
                                    <div class="box p-5 mr-5">
                                        <div class="text-base text-slate-500">Status Pompa 3</div>
                                        <div class="flex  mt-6">
                                            <div class="flex flex-row">
                                                <img alt="" class="w-8" src="{{ asset('images/power.svg') }}">
                                                <div id="pompa_3" class="text-3xl font-medium leading-8 ml-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            onValue,
            onChildAdded,
            query,
            limitToLast,
            orderByChild,
            get
        } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js";
        const firebaseConfig = {
            authDomain: "{{ config('firebase.projects.app.credentials.file') }}",
            databaseURL: "{{ config('firebase.projects.app.database.url') }}",
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase();
        const starCountRefSensor = ref(db, 'data');

        onValue(starCountRefSensor, (snapshot) => {
            const data = snapshot.val();
            console.log(data);
            document.getElementById("turbidity_sensor").innerHTML = data.turbidity_sensor.toFixed(2) + " NTU";
            document.getElementById("ph_sensor").innerHTML = data.ph_sensor.toFixed(2);
            document.getElementById("ultrasonic_sensor_1").innerHTML = data.ultrasonic_sensor.toFixed(1) + " CM";
            document.getElementById("ultrasonic_sensor_2").innerHTML = data.ultrasonic_sensor_1.toFixed(1) + " CM";
            document.getElementById("pompa_1").innerHTML = data.pompa_1 ? `<div class="text-success">ON</div>` : `<div class="text-danger">OFF</div>`;
            document.getElementById("pompa_2").innerHTML = data.pompa_2 ? `<div class="text-success">ON</div>` : `<div class="text-danger">OFF</div>`;
            document.getElementById("pompa_3").innerHTML = data.pompa_3 ? `<div class="text-success">ON</div>` : `<div class="text-danger">OFF</div>`;
            document.getElementById("information").innerHTML = `<div class="text-white p-2 ml-4 font-bold uppercase" style="background-color: #008080; border: 1px solid #d4d4d4; border-radius: 8px;">${data.keterangan}</div>`;
        });
    </script>
@endpush
