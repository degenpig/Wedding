<script setup>
    import { Head, useForm } from '@inertiajs/inertia-vue3';
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import { GoogleMap, Marker } from "vue3-google-map";
    import { ref, onMounted } from 'vue';

    const gmapKey = import.meta.env.VITE_GMAP_API_KEY;

    const marker = ref({ position: { lat: 10, lng: 10 } });
    const center = ref({ lat: 10, lng: 10 });
    const mapOptions = ref({
        disableDefaultUI: true,
    });
    const mapRef = ref(null);
    let id = 0;

    onMounted(() => {
        geolocate();
    });

    const geolocate = () => {
      navigator.geolocation.getCurrentPosition((position) => {
        marker.position = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        panToMarker();
      });
    }
    const handleMarkerDrag = (e) => {
      marker.value ={position: { lat: e.latLng.lat(), lng: e.latLng.lng() }};
    }
    const panToMarker = () => {
      mapRef.panTo(marker.position);
      mapRef.setZoom(18);
    }
    const handleMapClick = (e) => {
      marker.value ={position: { lat: e.latLng.lat(), lng: e.latLng.lng() }};
    }

    const form = useForm({
        bride_first_name: '',
        bride_second_name: '',
        bride_third_name: '',
        bride_family_name: '',
        bride_father_name: '',
        bride_mother_name: '',

        groom_first_name: '',
        groom_second_name: '',
        groom_third_name: '',
        groom_family_name: '',
        groom_father_name: '',
        groom_mother_name: '',

        event_title: '',
        welcome_msg: '',
        event_date: '',
        event_time: '',
        latitude: '',
        longitude: '',
        location: ''
    });

    const submit = () => {
        console.log(form.event_date);
        // TODO: call post store API
        // form.post(route('events.store'));
    };
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">أضافة حفل زفاف</h2>
        </template>
        <template #content>
        <div>
            <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="submit">
                <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">

                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <div>
                            <!-- TODO: the title should change to Arabic -->
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Bride Information</h3>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="bride-first-name" value="First Name" />
                                <TextInput
                                    id="bride-first-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_first_name"
                                    autofocus
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="bride-second-name" value="Second Name" />
                                <TextInput
                                    id="bride-second-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_second_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="bride-thrid-name" value="Third Name" />
                                <TextInput
                                    id="bride-thrid-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_third_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="bride-family-name" value="Family Name" />
                                <TextInput
                                    id="bride-family-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_family_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="bride-father-full-name" value="Father Full Name" />
                                <TextInput
                                    id="bride-father-full-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_father_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="bride-mother-full-name" value="Mother Full Name" />
                                <TextInput
                                    id="bride-mother-full-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.bride_mother_name"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">بيانات المعرس</h3>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="groom-first-name" value="First Name" />
                                <TextInput
                                    id="groom-first-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_first_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="groom-second-name" value="Second Name" />
                                <TextInput
                                    id="groom-second-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_second_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="groom-thrid-name" value="Third Name" />
                                <TextInput
                                    id="groom-thrid-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_third_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="groom-family-name" value="Family Name" />
                                <TextInput
                                    id="groom-family-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_family_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="groom-father-full-name" value="Father Full Name" />
                                <TextInput
                                    id="groom-father-full-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_father_name"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="groom-mother-full-name" value="Mother Full Name" />
                                <TextInput
                                    id="groom-mother-full-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.groom_mother_name"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <GoogleMap 
                            :api-key="gmapKey"
                            :center="center"
                            :zoom="5"
                            map-style-id="roadmap"
                            :options="mapOptions"
                            style="width: 100%; height: 500px"
                            ref="mapRef"
                            @click="handleMapClick"
                        >
                            <Marker
                                :key="id"
                                :options="{ position: marker.position }"
                                :clickable="true"
                                :draggable="true"
                                @drag="handleMarkerDrag"
                                @click="panToMarker"
                            />
                        </GoogleMap>
                        <p>Selected Position: {{ marker.position }}</p>
                    </div>

                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Event Information</h3>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4">
                            <div class="sm:col-span-3">
                                <InputLabel for="title" value="Event Title" />
                                <TextInput
                                    id="title"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.event_title"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="title" value="Invitation Message" />
                                <textarea
                                    id="title"
                                    cols="30"
                                    rows="5"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                    v-model="form.welcome_msg"
                                />
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="date" value="Event Date" />
                                <Datepicker
                                    v-model="form.event_date"
                                    format="yyyy-MM-dd"
                                    :enable-time-picker="false"
                                    position="right"
                                    :clearable="false"
                                    auto-apply
                                />
                            </div>
                            
                            <div class="sm:col-span-3">
                                <InputLabel for="time" value="Event Time" />
                                <Datepicker
                                    v-model="form.event_time"
                                    position="right"
                                    time-picker
                                    :clearable="false"
                                    auto-apply
                                />
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="google-lat" value="Latitude" />
                                <TextInput
                                    id="google-lat"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.latitude"
                                />
                            </div>

                            
                            <div class="sm:col-span-3">
                                <InputLabel for="google-lon" value="Longitude" />
                                <TextInput
                                    id="google-lon"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.longitude"
                                />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="address" value="Address" />
                            <TextInput
                                id="address"
                                class="mt-1 block w-full"
                                type="text"
                                v-model="form.location"
                            />
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</button>
                        <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mr-2">Save</button>
                    </div>
                </div>
            </form>
        </div>
        </template>
    </AuthenticatedLayout>
</template>
