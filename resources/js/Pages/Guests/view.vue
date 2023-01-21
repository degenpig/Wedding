<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { APISettings } from '@/config.js';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/24/solid'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
const actvie = true;


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">قائمة الضيوف</h2>
        </template>
        <template #content>

            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:items-center">

                    <div class="mt-4 ml-5  inline-flex w-50 text-right">
                        <a href="/guests/add" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">إضافة ضيف جديد</a>
                    </div>
                    <div class="mt-4 ml-5 inline-flex w-50 text-right">
                        <button v-on:click="addGroup" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 sm:w-auto">إضافة مجموعة</button>
                    </div>
                    <div class="w-full  sm:ml-5 mt-8">
                        <input type="text" name="search" id="search" v-model="search" v-on:keyup="searchGuests" class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="البحث عن ضيف" />

                    </div>
                </div>
                <div class="mt-4 flex flex-col">
                    <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class=" shadow">
                                <table class="min-w-full ">

                                    <tbody class="bg-white">
                                    <template v-for="group in directory">
                                        <tr class="border-t border-gray-200">
                                            <th colspan="5" scope="colgroup" class="bg-gray-50 px-4 py-2 text-right text-lg font-semibold text-gray-900 sm:px-6">{{ group.title }}</th>
                                        </tr>
                                        <tr v-for="(guest, guestIdx) in group.guest"  :class="[guestIdx === 0 ? 'border-gray-300' : 'border-gray-200', 'border-t']">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-md sm:pl-6">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 flex-shrink-0">
                                                        <div class="flex-shrink-0 flex items-center justify-center w-16 bg-indigo-500 text-white font-medium h-10 w-10 rounded-full ml-3" v-html="getAbbrev(guest.first_name,guest.family_name)"></div>
                                                    </div>
                                                    <div class="mr-4">
                                                        <div class="font-medium text-gray-900">{{ guest.first_name }} {{ guest.family_name }}</div>
                                                        <div class="text-gray-500">{{ guest.invitation_status_title}}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                                                <Menu as="div" class="relative inline-block text-right">
                                                    <div>
                                                        <MenuButton class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-500 px-1 py-1 ml-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                                                            <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                                        </MenuButton>
                                                    </div>

                                                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                                        <MenuItems class="absolute left-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                                            <div class="py-1">

                                                                <MenuItem v-slot="{ active }">
                                                                    <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">تعديل</a>
                                                                </MenuItem>
                                                                <MenuItem v-slot="{ active }">
                                                                    <a v-on:click="deleteGuest(guest.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">حذف</a>
                                                                </MenuItem>
                                                            </div>
                                                            <div class="py-1">

                                                                <MenuItem v-slot="{ active }">
                                                                    <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">نقل إلى مجموعة اخرى</a>
                                                                </MenuItem>
                                                            </div>
                                                            <div class="py-1">

                                                                <MenuItem v-slot="{ active }">
                                                                    <a v-on:click="sendSMS(guest.id)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"> SMS إرسال دعوة</a>
                                                                </MenuItem>
                                                                <MenuItem v-slot="{ active }">
                                                                    <a :href="getWhatsappLink(guest)" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Whatsapp إرسال دعوة</a>
                                                                </MenuItem>
                                                            </div>

                                                        </MenuItems>
                                                    </transition>
                                                </Menu>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <TransitionRoot as="template" :show="open">
                <Dialog as="div" class="relative z-10" @close="open = false">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                    </TransitionChild>

                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-sm sm:p-6">
                                    <div>
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-pink-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                            </svg>

                                        </div>
                                        <div class="mt-3 mb-5 text-center sm:mt-5">
                                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">إضافة مجموعة جديدة</DialogTitle>

                                        </div>
                                        <input type="text" name="group" id="group" v-model="group_name"  class="block w-full mb-10 rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="اسم المجموعة" />

                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm" @click="addNewGroup">تأكيد</button>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-offset-2 sm:text-sm" @click="open = false">إلغاء</button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </template>
    </AuthenticatedLayout>
</template>
<script>
export default {
    data() {
        return {
            directory: {},
            search:"",
            searchKeywords: "filter[guest_name]=",
            open:false,
            group_name:"",
            user:this.$inertia.page.props.auth.user,
            event:this.$inertia.page.props.auth.user.event,
        }
    },
    methods: {
        getWhatsappLink(guest){
            let phonenumber = guest.phone_code + guest.phone_number;
            let message =  'السيد '+guest.first_name+', يشرفنا دعوتكم لحضور حفل زفاف الجوهرة صباح الخالد الصباح وجابر ثامر جابر الأحمد، يرجى الضغط على رابط الدعوة الخاص بكم: ';

            let link = "whatsapp://send?abid="+phonenumber+"&text="+message;
            return link;
        },
        getGuests() {
            fetch(APISettings.baseURL + 'getAllGuest?' + this.searchKeywords + '&user_id='+this.user.id ,{
                method: 'POST',
                headers: APISettings.headers
            })
                .then(response => response.json())
                .then(response=> {
                    let self = this;
                    if(response.status == true){
                        console.log('success');
                        this.directory = response.data;
                        console.log(response);
                    }else{
                        console.log('error');
                        console.log(response);
                    }
                })

        },

        sendSMS(guest_id) {
            let data = {
                receiver_id: guest_id,
                event_id:this.event.id,
                invitation_type_id:1,
                user_id:this.user.id
            }
            console.log(this.group_name);
            fetch(APISettings.baseURL + 'sendInvitation',{
                method: 'POST',
                headers: APISettings.headers,
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(response=> {
                    let self = this;
                    if(response.status == true){
                        console.log('success');
                        this.getGuests();

                    }else{

                    }
                })

        },
        addGroup() {
            this.open = true;

        },
        addNewGroup() {
            if(this.group_name) {
                let data = {
                    title: this.group_name,
                    event_id:this.event.id,
                    user_id:this.user.id
                }
                console.log(this.group_name);
                fetch(APISettings.baseURL + 'createGroup',{
                    method: 'POST',
                    headers: APISettings.headers,
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        this.getGuests();
                        this.open = false;
                    })
                    .catch(error => {
                        console.error('Error:', error)
                    })
            }
            else {
                return;
            }

        },
        deleteGuest(guest_id) {
            let guest_data = {
                user_id:this.user.id,
                event_id:this.event.id,
                guest_id:guest_id,
            }
            fetch(APISettings.baseURL + 'deleteGuest' ,{
                method: 'POST',
                headers: APISettings.headers,
                body: JSON.stringify(guest_data),
            })
                .then(response => response.json())
                .then(response=> {
                    let self = this;
                    if(response.status == true){
                        console.log('success');
                        this.getGuests();

                    }else{

                    }
                })
        },
        searchGuests() {
            this.searchKeywords = "filter[guest_name]=" + this.search;
            this.getGuests();
        },
        getAbbrev(f_name,l_name){
            return f_name.charAt(0) + ''+l_name.charAt(0) ;
        },
    },
    mounted() {
        this.getGuests();
    }
}
</script>
