@extends('layouts.master')
@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-9">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Kualitas Air Kolam Ikan Nila
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                <img alt="" class="w-8" src="{{ asset('/images/phair.png')}}">
                                    <!-- <div class="ml-auto">

                                    </div> -->
                                </div>
                                <div id="pH_sensor" class="text-3xl font-medium leading-8 mt-6"></div>
                                <div class="text-base text-slate-500 mt-1">pH Air</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                <img alt="" class="w-8" src="{{ asset('/images/suhuair.png')}}">
                                    <div class="ml-auto">
                                    </div>
                                </div>
                                <div id="suhu_air" class="text-3xl font-medium leading-8 mt-6"></div>
                                <div class="text-base text-slate-500 mt-1">Suhu Air</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                <img alt="" class="w-8" src="{{ asset('/images/turbidity.png')}}">
                                    <!-- <div class="ml-auto">
                                    </div> -->
                                </div>
                                <div id="turbidity_sensor" class="text-3xl font-medium leading-8 mt-6"></div>
                                <div class="text-base text-slate-500 mt-1">Kekeruhan</div>
                            </div>
                        </div>
                    </div>
                          <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                <img alt="" class="w-8" src="{{ asset('/images/info.png')}}">
                                    <div class="ml-auto">
                                    </div>
                                </div>
                                <div id="kualitas_air" class="text-3xl font-medium leading-8 mt-6"></div>
                                <div class="text-base text-slate-500 mt-1">Kualitas Air</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        



            
            <!-- END: General Report -->
            <!-- BEGIN: Sales Report -->

            <!-- END: Sales Report -->
            <!-- BEGIN: Weekly Top Seller -->
            <!-- END: Weekly Top Seller -->
            <!-- BEGIN: Sales Report -->
            <!-- END: Sales Report -->
            <!-- BEGIN: Official Store -->

            <!-- END: Official Store -->
            <!-- BEGIN: Weekly Best Sellers -->
            <!-- END: Weekly Best Sellers -->
            <!-- BEGIN: General Report -->
            <!-- END: General Report -->
            <!-- BEGIN: Weekly Top Products -->
         
            <!-- END: Weekly Top Products -->
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
                                                <img alt="" class="w-8" src="{{ asset('images/power.png') }}">
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
                                                <img alt="" class="w-8" src="{{ asset('images/power.png') }}">
                                                <div id="pompa_2" class="text-3xl font-medium leading-8 ml-3"></div>
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
                </div>
            </div>
        </div>
    </div>
   
                <!-- BEGIN: Transactions -->

                <!-- END: Transactions -->
              
                </div>
                <!-- END: Recent Activities -->
                <!-- BEGIN: Important Notes -->

                <!-- END: Important Notes -->
                <!-- BEGIN: Schedules -->
                <!-- END: Schedules -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.21.0/firebase-app.js"
    import { getDatabase, ref, onValue, onChildAdded, query, limitToLast, orderByChild, get} from "https://www.gstatic.com/firebasejs/9.21.0/firebase-database.js"

    const firebaseConfig = {
        apiKey: "{{ config('firebase.projects.app.apiKey') }}",
        authDomain: "{{ config('firebase.projects.app.authDomain') }}",
        databaseURL: "{{ config('firebase.projects.app.database.url') }}",
    };

    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);
    const starCountRefSensor = ref(db, 'realtime');
    onValue(starCountRefSensor, (snapshot) => {
        const data = snapshot.val();
        console.log(data);
        document.getElementById("turbidity_sensor").innerHTML = data.turbidity_sensor.toFixed(2) + " NTU";
            document.getElementById("pH_sensor").innerHTML = data.pH_sensor;
            document.getElementById("suhu_air").innerHTML = data.water_temperature + " ℃";
            document.getElementById("kualitas_air").innerHTML = data.kualitas_air;
            document.getElementById("pompa_1").innerHTML = data.pompa1;
            document.getElementById("pompa_2").innerHTML = data.pompa2;
    });
</script>
@endpush
