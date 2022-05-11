<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <form action="/" method="post" class="relative text-gray-600">
                    @csrf 
                    <p class="ml-5 mt-2 mb-2"><strong>Search for your location here:</strong></p>
                    <input type="search" name="location" placeholder="Search" class="bg-gray border h-10 px-5 pr-10 ml-5 text-sm focus:outline-none">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">SUBMIT</button>
                  
   
                </form>
                @if(isset($location))
                    <!-- <div>
                        {{json_encode($location)}}
                    </div> -->
                    <!-- <img src="https://countryflagsapi.com/png/{{$location->country_code}}" alt="country-flag"> -->
                    
                    
                   <div class="flex">
                    <div class="w-64 cursor-pointer border b-gray-400 rounded flex flex-col justify-center items-center text-center p-6 bg-white ml-5 mt-10 mb-10 mr-10">
                        <div class="text-md font-bold flex flex-col text-gray-900">
                            <span class="uppercase">Today</span> 
                            <span class="font-normal text-gray-700 text-sm">{{ date("d.m.Y") }}</span>
                            <span class="text-black-700 mb-2">{{$location->query}}</span>
                        </div>
                        <div class="w-20 h-20 flex items-center justify-center">
                            <img src="{{$weather->condition->icon}}" alt="weather-icon">
                        </div>
                        <p class="text-gray-700 mb-2">{{$weather->condition->text}}</p>
                        <div class="text-3xl font-bold text-gray-900 mb-6">{{$weather->maxtemp_c}}º<span class="font-normal text-gray-700 mx-1">/</span>{{$weather->mintemp_c}}º</div>
                    </div>
                   
                    <div>
                        <table class="border-separate border p-6 mt-10 mb-10 mr-10 w-80 h-60">
                            <tbody>
                            <tr>
                                <td><strong>Location:</strong></td>
                                <td>{{$location->query}}</td>
                            </tr>
                            <tr>
                                <td><strong>Latitude:</strong></td>
                                <td>{{$location->latitude}}</td>
                            </tr>
                            <tr>
                                <td><strong>Longitude:</strong></td>
                                <td>{{$location->longitude}}</td>
                            </tr>
                            <tr>
                                <td><strong>Country</strong></td>
                                <td>{{$location->country}}</td>
                            </tr>
                            <tr>
                                <td><strong>Capital city:</strong></td>
                                <td>{{$location->capital}}</td>
                            </tr>
                            <tr>
                                <td><strong>Flag:</strong></td> 
                                <!-- flag api -->
                                <td><img class="border" src="https://countryflagsapi.com/png/{{$location->country_code}}" alt="country-flag" height="30" width="60"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <table class="border mt-10 mb-20 mr-10 text-center">
                        <thead>
                            <tr>
                                <th class="p-6">00:00 <br> - <br> 06:00</th>
                                <th class="p-6">06:00 <br> - <br> 15:00</th>
                                <th class="p-6">15:00 <br> - <br> 21:00</th>
                                <th class="p-6">21:00 <br> - <br> 00:00</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="p-5">{{$time0}}</td>
                                    <td class="p-5">{{$time1}}</td>
                                    <td class="p-5">{{$time2}}</td>
                                    <td class="p-5">{{$time3}}</td>
                                </tr>
                        </tbody>
                    </table>

                </div> 
                    <table class="border w-200 p-10 m-10 text-center">
                        <thead>
                            <tr>
                                <th class="p-6">Flag</th>
                                <th class="p-6">Country</th>
                                <th class="p-6">Number of visits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($var as $item)
                                <tr>
                                    <td class="p-5"><img class="border" src="https://countryflagsapi.com/png/{{$item->country_code}}" alt="country-flag" height="30" width="60"></td>
                                    <td class="p-5">{{$item->country}}</td>
                                    <td class="p-5">{{$item->pocet}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    
                    
                @endif
                <div id="map" class="h-96"></div>
            </div>
        </div>
    </div>
    @if(isset($location))
        <script>
            var map = L.map('map').setView([{{$location->latitude}}, {{$location->longitude}}], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            @foreach(\App\Models\Location::all() as $location)
                L.marker([{{$location->latitude}},{{$location->longitude}}]).addTo(map)
                    .bindPopup('<img class="border" src="https://countryflagsapi.com/png/{{$location->country_code}}" alt="country-flag" height="25" width="50"> <br> <strong>Location:</strong> {{$location->query}} <br> <strong>Latitude:</strong> {{$location->latitude}} <br> <strong>Longitude:</strong> {{$location->longitude}} <br> <strong>Country:</strong> {{$location->country}} <br> <strong>Capital city:</strong> {{$location->capital}} <br>')
            @endforeach
        </script>
    @endif
        
</x-app-layout>