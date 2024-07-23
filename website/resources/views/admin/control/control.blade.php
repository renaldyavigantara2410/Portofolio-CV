@extends('layouts.master')

@section('content')
    <!-- BEGIN: General Report -->
    <div class="col-span-12 mt-5 md:mt-8">
        <div class="flex items-center h-10 intro-y">
            <h2 class="mr-5 text-lg font-medium truncate">
                Status Kontrol
            </h2>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5 mb-10">
            <!-- Display Jadwal Pakan -->
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="p-5 box">
                        <div class="flex flex-row items-center justify-between lg:justify-between lg:items-center">
                        <div id="display_jadwal_pakan">
                        <img alt="" class="w-8" src="{{ asset('/images/servobagus.png')}}">
                                <div class="mt-1 text-base text-slate-500">Jadwal Pakan Saat Ini</div>
                                <div id="jadwalPakanDisplay1" class="mt-2 text-lg font-medium text-slate-700"></div>
                                <div id="jadwalPakanDisplay2" class="mt-2 text-lg font-medium text-slate-700"></div>
                                <div class="mt-1 text-base text-slate-500">Jumlah Putaran Servo 1</div>
                                <div id="putaranServoDisplay1" class="mt-2 text-lg font-medium text-slate-700"></div>
                                <div class="mt-1 text-base text-slate-500">Jumlah Putaran Servo 2</div>
                                <div id="putaranServoDisplay2" class="mt-2 text-lg font-medium text-slate-700"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Jadwal Pakan -->
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                 <h2 class="mr-5 text-lg font-medium truncate">
                Pengaturan Pemberian Pakan
            </h2>
                <div class="report-box zoom-in">
                    <div class="p-5 box">
                        <div class="flex flex-row items-center justify-between lg:justify-between lg:items-center">
                            <div id="jadwal_pakan">
                                <div class="mt-1 text-base text-slate-500">Masukkan Jadwal Pakan 1</div>
                                <input type="text" id="jadwalInput1" class="mt-2 input w-full border" placeholder="HH:MM">
                                <div class="mt-1 text-base text-slate-500">Masukkan Jadwal Pakan 2</div>
                                <input type="text" id="jadwalInput2" class="mt-2 input w-full border" placeholder="HH:MM">

                                <!-- Input Jumlah Putaran Servo 1 -->
                                <div class="mt-1 text-base text-slate-500">Masukkan Jumlah Putaran Servo 1</div>
                                <input type="number" id="putaranServoInput1" class="mt-2 input w-full border"
                                    placeholder="Jumlah Putaran">

                                <!-- Input Jumlah Putaran Servo 2 -->
                                <div class="mt-1 text-base text-slate-500">Masukkan Jumlah Putaran Servo 2</div>
                                <input type="number" id="putaranServoInput2" class="mt-2 input w-full border"
                                    placeholder="Jumlah Putaran">

                                <button id="submitJadwal" class="mt-2 btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: General Statistic -->
    <!-- <div class="intro-y box col-span-12 lg:mt-8 mt-3"> -->


    <div class="grid grid-cols-12 gap-4 p-5 bg-slate-300/[4] border-8 rounded-md">
            <script type="module">
                import {
                    initializeApp
                } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-app.js";
                import {
                    getDatabase,
                    ref,
                    set,
                    get,
                    child
                } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js";

                const firebaseConfig = {
                    apiKey: "{{ config('firebase.projects.app.apiKey') }}",
                    authDomain: "{{ config('firebase.projects.app.authDomain') }}",
                    databaseURL: "{{ config('firebase.projects.app.database.url') }}",
                    projectId: "{{ config('firebase.projects.app.projectId') }}",
                    storageBucket: "{{ config('firebase.projects.app.storageBucket') }}",
                    messagingSenderId: "{{ config('firebase.projects.app.messagingSenderId') }}",
                    appId: "{{ config('firebase.projects.app.appId') }}"
                };

                const app = initializeApp(firebaseConfig);
                const db = getDatabase();

                const submitJadwal = document.getElementById("submitJadwal");
                const jadwalInput1 = document.getElementById("jadwalInput1");
                const jadwalInput2 = document.getElementById("jadwalInput2");
                const putaranServoInput1 = document.getElementById("putaranServoInput1");
                const putaranServoInput2 = document.getElementById("putaranServoInput2");
                const jadwalPakanDisplay1 = document.getElementById("jadwalPakanDisplay1");
                const jadwalPakanDisplay2 = document.getElementById("jadwalPakanDisplay2");
                const putaranServoDisplay1 = document.getElementById("putaranServoDisplay1");
                const putaranServoDisplay2 = document.getElementById("putaranServoDisplay2");

                function isValidTime(time) {
                    const regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
                    return regex.test(time);
                }

                submitJadwal.addEventListener("click", function() {
                    const jadwal1 = jadwalInput1.value;
                    const jadwal2 = jadwalInput2.value;
                    const putaranServo1 = putaranServoInput1.value;
                    const putaranServo2 = putaranServoInput2.value;

                    if (isValidTime(jadwal1) && isValidTime(jadwal2) && putaranServo1 && putaranServo1 > 0 && putaranServo2 && putaranServo2 > 0) {
                        set(ref(db, 'jadwalPakan'), {
                            jadwal1: jadwal1,
                            jadwal2: jadwal2,
                            putaranServo1: putaranServo1,
                            putaranServo2: putaranServo2
                        }).then(() => {
                            alert("Jadwal pakan dan putaran servo berhasil disimpan");
                            jadwalPakanDisplay1.innerText = jadwal1;
                            jadwalPakanDisplay2.innerText = jadwal2;
                            putaranServoDisplay1.innerText = putaranServo1;
                            putaranServoDisplay2.innerText = putaranServo2;
                        }).catch((error) => {
                            console.error("Error writing document: ", error);
                        });
                    } else {
                        alert("Masukkan jadwal pakan dengan format HH:MM yang valid dan jumlah putaran servo yang valid");
                    }
                });

                window.addEventListener("load", function() {
                    const dbRef = ref(db);
                    get(child(dbRef, `jadwalPakan`)).then((snapshot) => {
                        if (snapshot.exists()) {
                            const jadwal = snapshot.val();
                            jadwalPakanDisplay1.innerText = jadwal.jadwal1 || "Belum ada jadwal pakan 1";
                            jadwalPakanDisplay2.innerText = jadwal.jadwal2 || "Belum ada jadwal pakan 2";
                            putaranServoDisplay1.innerText = jadwal.putaranServo1 || "Belum ada jumlah putaran servo 1";
                            putaranServoDisplay2.innerText = jadwal.putaranServo2 || "Belum ada jumlah putaran servo 2";
                        } else {
                            jadwalPakanDisplay1.innerText = "Belum ada jadwal pakan 1";
                            jadwalPakanDisplay2.innerText = "Belum ada jadwal pakan 2";
                            putaranServoDisplay1.innerText = "Belum ada jumlah putaran servo 1";
                            putaranServoDisplay2.innerText = "Belum ada jumlah putaran servo 2";
                        }
                    }).catch((error) => {
                        console.error("Error getting data: ", error);
                    });
                });
            </script>
        </div>
    </div>
    <!-- END: General Statistic -->
@endsection
