<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import {Countdown} from 'vue3-flip-countdown';
import { APISettings } from '@/config.js';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">الإحصائيات</h2>
        </template>
        <template #content>
            <div class="mt-6 grid  gap-y-6 gap-x-4  grid-cols-1 md:grid-cols-6">

                <div class="col-span-6 ">
                    <p class="text-xl">العد التنازلي</p>
                    <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <Countdown :flipAnimation="false" />
                    </div>
                </div>
                <div class="col-span-6 ">
                    <p class="text-xl">الإحصائيات</p>
                    <div class="rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <div class="flex justify-between w-full ltr:divide-x">
                            <div class="text-center">
                                <p class="text-md font-bold">{{ statistics["invited"] }}</p>
                                <div class="statistics-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">المدعوين</p>
                            </div>
                            <div class="text-center">
                                <p class="text-md font-bold">{{ statistics["accpeted"] }}</p>
                                <div class="statistics-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>

                                </div>
                                <p class="text-gray-500 text-sm">الحضور</p>
                            </div>
                            <div class="text-center">
                                <p class="text-md font-bold">{{ statistics["decline"] }}</p>
                                <div class="statistics-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>


                                </div>
                                <p class="text-gray-500 text-sm">الاعتذار</p>
                            </div>
                            <div class="text-center">
                                <p class="text-md font-bold">{{ statistics["notDecided"] }}</p>
                                <div class="statistics-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9v6m-4.5 0V9M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                </div>
                                <p class="text-gray-500 text-sm">لم يقرر</p>
                            </div>
                            <div class="text-center">
                                <p class="text-md font-bold">{{ statistics["pending"] }}</p>
                                <div class="statistics-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>


                                </div>
                                <p class="text-gray-500 text-sm">لم يرد</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 ">
                    <p class="text-xl">Wedding Entry Status</p>
                    <div class="h-[7rem] p-3 flex items-center bg-white rounded-md drop-shadow-lg shadow-lg">
                        <div class="flex justify-between w-full">
                            <img src="https://media.istockphoto.com/id/690054536/ru/%D0%B2%D0%B5%D0%BA%D1%82%D0%BE%D1%80%D0%BD%D0%B0%D1%8F/%D0%BA%D0%BE%D0%B4-%D0%BA%D1%80.jpg?s=612x612&w=0&k=20&c=rbXV5kI4S5mioa9wcvkAJQ3R-A9IahTmfis8Ol1VxZM=" alt="QR" class="w-20 h-auto" />
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-lg">0/{{ statistics["invited"] }}</p>
                                <p class="text-gray-300">Scanned Invitations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
<script>
export default {
    data() {
        return {
            statistics: {},
            user:this.$inertia.page.props.auth.user,
            event:this.$inertia.page.props.auth.user.event,
        }
    },
    methods: {
        getStatistics() {
            fetch(APISettings.baseURL + 'getAllInviatationStatus?user_id='+this.user.id ,{
                method: 'GET',
                headers: APISettings.headers
            })
                .then(response => response.json())
                .then(response=> {
                    let self = this;
                    if(response.status == true){
                        console.log('success');
                        this.statistics = response.data;
                        console.log(response);
                    }else{
                        console.log('error');
                        console.log(response);
                    }
                })

        },


    },
    mounted() {
        this.getStatistics();
    }
}
</script>




