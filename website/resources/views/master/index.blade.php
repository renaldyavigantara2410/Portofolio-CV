@extends('layouts.master')

@section('content')
    <div class="content content--top-nav">
        <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Master Data
            </h2>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="lg:flex lg:gap-2 intro-y">
                    <div class="box py-2 w-full pr-10">
                        <ul class="nav nav-pills" role="tablist">
                            <li id="details-tab" class="nav-item flex-1" role="presentation">
                                <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#details"
                                    type="button" role="tab" aria-controls="details" aria-selected="false"> Data
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 mt-5">
                    <div class="col-span-12 lg:col-span-4 box p-5 cursor-pointer zoom-in">
                        <div class="font-medium text-base">pH</div>
                        <div id="last_phVolt" class="text-slate-500"></div>
                        <div class="font-medium text-base mt-5">Slope</div>
                        <div id="last_phSlope" class="text-slate-500"></div>
                        <div class="font-medium text-base mt-5">Intercept</div>
                        <div id="last_phIntercept" class="text-slate-500"></div>
                    </div>
                    <div class="col-span-12 lg:col-span-8 box p-5">
                        <div class="box flex">
                            <input id="phVoltInput" name="phVoltInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input volt...">
                            <button id="savePhVoltBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                        <div class="box flex mt-5">
                            <input id="phSlopeInput" name="phSlopeInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input slope...">
                            <button id="savePhSlopeBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                        <div class="box flex mt-5">
                            <input id="phInterceptInput" name="phInterceptInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input y_intercept...">
                            <button id="savePhInterceptBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-4 box p-5 cursor-pointer zoom-in">
                        <div class="font-medium text-base">Turbidity</div>
                        <div id="last_turbidityVolt" class="text-slate-500"></div>
                        <div class="font-medium text-base mt-5">Slope</div>
                        <div id="last_turbiditySlope" class="text-slate-500"></div>
                        <div class="font-medium text-base mt-5">Intercept</div>
                        <div id="last_turbidityIntercept" class="text-slate-500"></div>
                    </div>
                    <div class="col-span-12 lg:col-span-8 box p-5">
                        <div class="box flex">
                            <input id="turbidityVoltInput" name="turbidityVoltInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input volt...">
                            <button id="saveTurbidityVoltBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                        <div class="box flex mt-5">
                            <input id="turbiditySlopeInput" name="turbiditySlopeInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input slope...">
                            <button id="saveTurbiditySlopeBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                        <div class="box flex mt-5">
                            <input id="turbidityInterceptInput" name="turbidityInterceptInput" type="number" step="any"
                                class="form-control py-3 px-4 w-full bg-slate-100 border-slate-200/60 pr-10"
                                placeholder="Input y_intercept...">
                            <button id="saveTurbidityInterceptBtn" class="btn btn-primary ml-2">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4">
                <div class="intro-y pr-1">
                    <div class="box p-2">
                        <ul class="nav nav-pills" role="tablist">
                            <li id="details-tab" class="nav-item flex-1" role="presentation">
                                <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#details"
                                    type="button" role="tab" aria-controls="details" aria-selected="false"> Details
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="ticket" class="tab-pane active" role="tabpanel" aria-labelledby="ticket-tab">
                        <div class="box p-2 mt-5">
                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#add-item-modal"
                                class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white dark:bg-darkmode-600 hover:bg-slate-100 dark:hover:bg-darkmode-400 rounded-md">
                                <i data-feather="edit" class="w-4 h-4 text-slate-500 mr-2"></i>
                                <div class="max-w-[50%] truncate mr-1">pH Value</div>
                                <div id="last_phVal" class="ml-auto font-medium"></div>
                            </a>
                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#add-item-modal"
                                class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white dark:bg-darkmode-600 hover:bg-slate-100 dark:hover:bg-darkmode-400 rounded-md">
                                <i data-feather="edit" class="w-4 h-4 text-slate-500 mr-2"></i>
                                <div class="max-w-[50%] truncate mr-1">Turbidity Value</div>
                                <div id="last_turbidityVal" class="ml-auto font-medium"></div>
                            </a>
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
            get,
            update,
            set
        } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js";


        const firebaseConfig = {
            authDomain: "{{ config('firebase.projects.app.credentials.file') }}",
            databaseURL: "{{ config('firebase.projects.app.database.url') }}",
        };
        const app = initializeApp(firebaseConfig);
        const db = getDatabase();
        const refValue = ref(db, 'realtime');
        const refMaster = ref(db, 'data');
        console.log(refMaster);

        onValue(refValue, (snapshot) => {
            const data = snapshot.val();
            console.log(data);
            const lastPhVal = document.getElementById("last_phVal").innerHTML = data.ph_sensor.toFixed(2);
            const lastTurbidityVal = document.getElementById("last_turbidityVal").innerHTML = data.turbidity_sensor.toFixed(2) + " NTU";
        });

        onValue(refMaster, (snapshot) => {
            const data = snapshot.val();
            const lastPhVolt = document.getElementById("last_phVolt").innerHTML = data.ph_sensor.volt + "V";
            const lastPhSlope = document.getElementById("last_phSlope").innerHTML = data.ph_sensor.slope;
            const lastPhIntercept = document.getElementById("last_phIntercept").innerHTML = data.ph_sensor.y_intercept;
            const lastTurbidityVolt = document.getElementById("last_turbidityVolt").innerHTML = data.turbidity_sensor.volt + "V";
            const lastTurbiditySlope = document.getElementById("last_turbiditySlope").innerHTML = data.turbidity_sensor.slope;
            const lastTurbidityIntercept = document.getElementById("last_turbidityIntercept").innerHTML = data.turbidity_sensor.y_intercept;
        });

        let phVoltInput = document.querySelector('input[name="phVoltInput"]');
        let phSlopeInput = document.querySelector('input[name="phSlopeInput"]');
        let phInterceptInput = document.querySelector('input[name="phInterceptInput"]');
        let turbidityVoltInput = document.querySelector('input[name="turbidityVoltInput"]');
        let turbiditySlopeInput = document.querySelector('input[name="turbiditySlopeInput"]');
        let turbidityInterceptInput = document.querySelector('input[name="turbidityInterceptInput"]');

        let savePhVoltBtn = document.querySelector('#savePhVoltBtn');
        let savePhSlopeBtn = document.querySelector('#savePhSlopeBtn');
        let savePhInterceptBtn = document.querySelector('#savePhInterceptBtn');
        let saveTurbidityVoltBtn = document.querySelector('#saveTurbidityVoltBtn');
        let saveTurbiditySlopeBtn = document.querySelector('#saveTurbiditySlopeBtn');
        let saveTurbidityInterceptBtn = document.querySelector('#saveTurbidityInterceptBtn');

        savePhVoltBtn.addEventListener('click', function() {
            if (phVoltInput.value !== "" && phVoltInput.value !== null) {
                updateData("ph_sensor", "volt", parseFloat(phVoltInput.value));
                phVoltInput.value = "";
            }
        });

        savePhSlopeBtn.addEventListener('click', function() {
            if (phSlopeInput.value !== "" && phSlopeInput.value !== null) {
                updateData("ph_sensor", "slope", parseFloat(phSlopeInput.value));
                phSlopeInput.value = "";
            }
        });
        savePhInterceptBtn.addEventListener('click', function() {
            if (phInterceptInput.value !== "" && phInterceptInput.value !== null) {
                updateData("ph_sensor", "y_intercept", parseFloat(phInterceptInput.value));
                phInterceptInput.value = "";
            }
        });

        saveTurbidityVoltBtn.addEventListener('click', function() {
            if (turbidityVoltInput.value !== "" && turbidityVoltInput.value !== null) {
                updateData("turbidity_sensor", "volt", parseFloat(turbidityVoltInput.value));
                turbidityVoltInput.value = "";
            }
        });

        saveTurbiditySlopeBtn.addEventListener('click', function() {
            if (turbiditySlopeInput.value !== "" && turbiditySlopeInput.value !== null) {
                updateData("turbidity_sensor", "slope", parseFloat(turbiditySlopeInput.value));
                turbiditySlopeInput.value = "";
            }
        });

        saveTurbidityInterceptBtn.addEventListener('click', function() {
            if (turbidityInterceptInput.value !== "" && turbidityInterceptInput.value !== null) {
                updateData("turbidity_sensor", "y_intercept", parseFloat(turbidityInterceptInput.value));
                turbidityInterceptInput.value = "";
            }
        });

        function updateData(sensorType, dataKey, value) {
            const sensorPath = `${sensorType}/${dataKey}`;
            update(refMaster, {
                    [sensorPath]: value
                })
                .then(function() {})
                .catch(function(error) {});
        }
    </script>
@endpush
