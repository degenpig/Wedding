<script setup>
import { computed, ref } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
    Combobox,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
} from '@headlessui/vue'
import {
    Bars3BottomLeftIcon,
    BellIcon,
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'

import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import { ChevronRightIcon } from '@heroicons/vue/24/outline'
const showingNavigationDropdown = ref(false);
import {

} from '@headlessui/vue'

const people = [
    {
        id: 1,
        name: 'Leslie Alexander',
        phone: '1-493-747-9031',
        email: 'lesliealexander@example.com',
        role: 'Co-Founder / CEO',
        url: '#',
        profileUrl: '#',
        imageUrl:
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    // More people...
]

const recent = []


const query = ref('')
const filteredPeople = computed(() =>
    query.value === ''
        ? []
        : people.filter((person) => {
            return person.name.toLowerCase().includes(query.value.toLowerCase())
        })
)

function onSelect(person) {
    window.location = person.url
}
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 ml-5"
                                    />
                                </Link>

                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    الرئيسية
                                </NavLink>
                                <NavLink :href="route('guests')" :active="route().current('guests')">
                                    الضيوف
                                </NavLink>
                                <NavLink :href="route('invitations')" :active="route().current('invitations')">
                                    الدعوات
                                </NavLink>
                                <NavLink :href="route('seating')" :active="route().current('seating')">
                                    القاعة والجلوس
                                </NavLink>
                                <NavLink :href="route('settings')" :active="route().current('settings')">
                                    الاعدادات
                                </NavLink>
                            </div>
                        </div>


                        <div class="hidden sm:flex sm:items-center sm:ml-6">


                            <button type="button" class=" ml-2 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">View notifications</span>
                                <BellIcon class="h-6 w-6" aria-hidden="true" />
                            </button>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >

                                                {{ $page.props.auth.user.name }}
                                                <img class="h-8 w-8 mr-2 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />

                                                <svg
                                                    class="mr-2 -ml-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> الملف الشخصي </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            تسجيل الخروج
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">

                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            الرئيسية
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('guests')" :active="route().current('guests')">
                            الضيوف
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('invitations')" :active="route().current('invitations')">
                            الدعوات
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('seating')" :active="route().current('seating')">
                            القاعة والجلوس
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('settings')" :active="route().current('settings')">
                            الاعدادات
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> الملف الشخصي </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                تسجيل الخروج
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow   lg:px-48 p-5" v-if="$slots.header">
                <div class="-mr-2 flex items-center">
                    <div class=" flex">
                        <slot name="header" />
                    </div>
                    <div class="flex-grow text-left">
                        <button @click="openSearch()" type="button" class=" ml-2 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex-end">
                            <span class="sr-only">View notifications</span>
                            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </button>
                    </div>
                </div>

            </header>

            <!-- Page Content -->
            <main>
                <div class="mx-auto max-w-7xl lg:px-8 p-5">
                    <!-- Replace with your content -->

                    <slot name="content" />
                </div>
            </main>

        </div>
    </div>
    <TransitionRoot :show="open" as="template" @after-leave="query = ''" appear>
        <Dialog as="div" class="relative z-10" @close="open = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                    <DialogPanel class="mx-auto max-w-3xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                        <Combobox v-slot="{ activeOption }" @update:modelValue="onSelect">
                            <div class="relative">
                                <MagnifyingGlassIcon class="pointer-events-none absolute top-3.5 right-4 h-5 w-5 text-gray-400" aria-hidden="true" />
                                <ComboboxInput class="h-12 w-full border-0 bg-transparent pr-11 pl-4 text-gray-800 placeholder-gray-400 focus:ring-0 sm:text-sm" placeholder="ابحث..." @change="query = $event.target.value" />
                            </div>

                            <ComboboxOptions v-if="query === '' || filteredPeople.length > 0" class="flex divide-x divide-gray-100" as="div" static hold>
                                <div :class="['max-h-96 min-w-0 flex-auto scroll-py-4 overflow-y-auto px-6 py-4', activeOption && 'sm:h-96']">
                                    <h2 v-if="query === ''" class="mt-2 mb-4 text-xs font-semibold text-gray-500">اخر الاشخاص الذي تم البحث عليهم</h2>
                                    <div hold class="-mx-2 text-sm text-gray-700">
                                        <ComboboxOption v-for="person in query === '' ? recent : filteredPeople" :key="person.id" :value="person" as="template" v-slot="{ active }">
                                            <div :class="['group flex cursor-default select-none items-center rounded-md p-2', active && 'bg-gray-100 text-gray-900']">
                                                <img :src="person.imageUrl" alt="" class="h-6 w-6 flex-none rounded-full" />
                                                <span class="mr-3 flex-auto truncate">{{ person.name }}</span>
                                                <ChevronRightIcon v-if="active" class="mr-3 h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                                            </div>
                                        </ComboboxOption>
                                    </div>
                                </div>

                                <div v-if="activeOption" class="hidden h-96 w-1/2 flex-none flex-col divide-y divide-gray-100 overflow-y-auto sm:flex">
                                    <div class="flex-none p-6 text-center">
                                        <img :src="activeOption.imageUrl" alt="" class="mx-auto h-16 w-16 rounded-full" />
                                        <h2 class="mt-3 font-semibold text-gray-900">
                                            {{ activeOption.name }}
                                        </h2>
                                        <p class="text-sm leading-6 text-gray-500">{{ activeOption.role }}</p>
                                    </div>
                                    <div class="flex flex-auto flex-col justify-between p-6">
                                        <dl class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm text-gray-700">
                                            <dt class="col-end-1 font-semibold text-gray-900">Phone</dt>
                                            <dd>{{ activeOption.phone }}</dd>
                                            <dt class="col-end-1 font-semibold text-gray-900">URL</dt>
                                            <dd class="truncate">
                                                <a :href="activeOption.url" class="text-indigo-600 underline">
                                                    {{ activeOption.url }}
                                                </a>
                                            </dd>
                                            <dt class="col-end-1 font-semibold text-gray-900">Email</dt>
                                            <dd class="truncate">
                                                <a :href="`mailto:${activeOption.email}`" class="text-indigo-600 underline">
                                                    {{ activeOption.email }}
                                                </a>
                                            </dd>
                                        </dl>
                                        <button type="button" class="mt-6 w-full rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Send message</button>
                                    </div>
                                </div>
                            </ComboboxOptions>

                            <div v-if="query !== '' && filteredPeople.length === 0" class="py-14 px-6 text-center text-sm sm:px-14">
                                <UsersIcon class="mx-auto h-6 w-6 text-gray-400" aria-hidden="true" />
                                <p class="mt-4 font-semibold text-gray-900">No people found</p>
                                <p class="mt-2 text-gray-500">We couldn’t find anything with that term. Please try again.</p>
                            </div>
                        </Combobox>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script>

export default {
    data() {
        return {
            open : false,
        }
    },
    methods: {
        openSearch() {
            this.open = true;
            console.log(this.open)
        },
        closeDialog() {
            this.open = false;
            console.log(this.open)
        }

    }
}
</script>



}
