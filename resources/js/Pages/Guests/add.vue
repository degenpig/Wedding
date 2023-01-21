<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { APISettings } from '@/config.js';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة ضيف</h2>
        </template>
        <template #content>
        <div>
            <form class="space-y-8" @submit.prevent="submit">
                <div class="space-y-8  sm:space-y-5">

                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">معلومات الضيف</h3>
                        </div>
                        <div>
                            <InputLabel for="guest-title" value="اللقب" />
                            <TextInput
                                id="guest-title"
                                class="mt-1 block w-full"
                                type="text"
                                v-model="form.guest_title"
                                autofocus
                            />
                        </div>

                        <div class="mt-6 grid  gap-y-4 gap-x-4">
                            <div class="col-span-3">
                                <InputLabel for="guest-first-name" value="الاسم الأول" />
                                <TextInput
                                    id="guest-first-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.guest_first_name"
                                />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="guest-second-name" value="الاسم الثاني" />
                                <TextInput
                                    id="guest-second-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.guest_second_name"
                                />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="guest-thrid-name" value="الاسم الثالث" />
                                <TextInput
                                    id="guest-thrid-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.guest_third_name"
                                />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="guest-family-name" value="اسم العائلة" />
                                <TextInput
                                    id="guest-family-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.guest_family_name"
                                />
                            </div>
                            <div class="col-span-12">
                                <InputLabel for="guest-group" value="المجموعة" />
                                <select v-model="form.group_name"  v-bind:value="form.group_name" name="group_name"  id="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 fc">
                                    <option disabled>اختيار المجموعة</option>
                                    <option v-for="option in groupOptions" v-bind:value="option.title">{{ option.title }}</option>

                                </select>
                            </div>
                        </div>

                        <button v-on:click="addGroup" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 sm:w-auto">إضافة محموعة جديدة</button>
                    </div>

                    <div class="space-y-3 pt-2">

                        <div class="mt-6 grid  gap-y-6 gap-x-4 grid-cols-6">
                            <div class="col-span-6">
                                <InputLabel for="title" value="البريد الإلكتروني" />
                                <TextInput
                                    id="title"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="form.contact_email"
                                />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="title" value="رقم الهاتف" />
                                <vue-tel-input v-model="form.contact_phone" placeholder="Enter your p" options="options" class="my-tel-input" ref="phoneinput" ></vue-tel-input>

                            </div>
                            <div class="col-span-3">
                                <InputLabel for="date" value="رقم الواتس اب" />
                                <vue-tel-input v-model="form.contact_whatsapp" placeholder="ادخل رقم الواتس اب" options="options" class="my-tel-input"  ref="whatsappinput"></vue-tel-input>
                            </div>
                        </div>
                    </div>
                </div>
                إضافة مرافقين
                <Switch v-model="isToggled" :class="[isToggled ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true" :class="[isToggled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full absolute left-0 bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                </Switch>
                <div v-if="isToggled">
                    <slot>
                        <div v-for="(input, index) in form.guest_companions" :key="index" class="mt-6 grid grid-cols-2 gap-2">
                            <div class="col">
                                <InputLabel for="guest-first-name" value="الاسم الأول" />
                                <TextInput
                                    id="guest-first-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="input.first_name"
                                />
                            </div>

                            <div class="col">
                                <InputLabel for="guest-second-name" value="الاسم الثاني" />
                                <TextInput
                                    id="guest-second-name"
                                    class="mt-1 block w-full"
                                    type="text"
                                    v-model="input.family_name"
                                />
                            </div>
                            <div class="col">
                                <button @click="deleteCompanion(index)" class="bg-red-500 text-white p-2 rounded">حذف</button>
                            </div>

                        </div>
                        <button v-on:click="addCompanion" type="button" class="inline-flex items-center mt-4 justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 sm:w-auto">إضافة مرافق</button>

                    </slot>
                </div>


                <div class="pt-5">
                    <div class="full-width">
                        <button type="submit" @click="addGuest" class="bg-indigo-500 text-white py-2 px-4 rounded-lg w-full">اتمام وحفظ</button>
                    </div>
                </div>
            </form>
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
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css'
import { Switch } from '@headlessui/vue'
export default {
    components: {
        VueTelInput,
        Switch,

    },
    data() {
        return {
            directory: {},
            isToggled: false,
            options: {

                ignoredCountries: ['il'],
                preferredCountries: ['kw','sa', 'ae', 'bh', 'om', 'qa'],
            },
            search:"",
            searchKeywords: "filter[guest_name]=",
            open:false,
            group_name:"",
            companian_available:0,
            user:this.$inertia.page.props.auth.user,
            event:this.$inertia.page.props.auth.user.event,
            groupOptions:[{
                title:"",
                id:"",

            }],
            form : {
                guest_title: '',
                guest_first_name: '',
                guest_second_name: '',
                guest_third_name: '',
                guest_family_name: '',
                contact_email: '',
                contact_phone_code: '',
                contact_phone: '',
                contact_whatsapp_code: '',
                contact_whatsapp: '',
                group_name:'اختيار المجموعة',
                guest_companions:[
                    {}
                ]
            }
        }
    },
    methods: {
        getGroups() {
            fetch(APISettings.baseURL + 'getAllGroup?user_id='+this.user.id+'&event_id='+this.event.id ,{
                method: 'get',
                headers: APISettings.headers
            })
                .then(response => response.json())
                .then(response=> {
                    let self = this;
                    if(response.status == true){
                        this.groupOptions = response.data;
                    }else{
                    }
                })

        },
        addGroup() {
            this.group_name = '';
            this.open = true;

        },
        addNewGroup() {
            if(this.group_name) {
                let group_data = {
                    title: this.group_name,
                    event_id:this.event.id,
                    user_id:this.user.id
                }
                fetch(APISettings.baseURL + 'createGroup',{
                    method: 'POST',
                    headers: APISettings.headers,
                    body: JSON.stringify(group_data),
                })
                    .then(response => response.json())
                    .then(data => {
                        this.groupOptions.push(group_data);
                        this.form.group_name = group_data.title;
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

        addGuest() {

            let guest_data = {
                event: this.event.id,
                user_id:this.user.id,
                group: this.form.group_name,
                title:this.form.guest_title,
                first_name:this.form.guest_first_name,
                second_name: this.form.guest_second_name,
                third_name:this.form.guest_third_name,
                family_name:this.form.guest_family_name,
                email_id: this.form.contact_email,
                phone_code:"+965",
                phone_number:this.form.contact_phone,
                whatsapp_phone_code:"+965",
                whatsapp_phone_number: this.form.contact_whatsapp,
                companian_available:this.companian_available,
                companian:this.form.guest_companions,
            }
            fetch(APISettings.baseURL + 'createGuest',{
                method: 'POST',
                headers: APISettings.headers,
                body: JSON.stringify(guest_data),
            })
                .then(response => response.json())
                .then(data => {
                    this.$inertia.visit('/guests');
                })
                .catch(error => {
                    console.error('Error:', error)
                })

        },

        addCompanion() {
            this.companian_available = 1;
            this.form.guest_companions.push({
                first_name: '',
                family_name: '',
            })
            console.log(this.form.guest_companions);
        },
        deleteCompanion(index) {
            this.form.guest_companions.splice(index, 1)
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
        this.getGroups();

    }
}
</script>
