<template>
    <!-- give the sidebar z-50 class so its higher than the navbar if you want to see the logo -->
    <!-- you will need to add a little "X" button next to the logo in order to close it though -->
    <div class="w-1/2 md:w-1/3 lg:w-64 fixed md:top-0 md:left-0 h-screen lg:block bg-gray-100 border-r z-30" :class="sideBarOpen ? '' : 'hidden'" id="main-nav">

        <div class="w-full h-20 border-b flex px-4 items-center mb-8">
            <p class="font-semibold text-2xl text-blue-400 pl-4">Your SwissCar</p>
        </div>

        <div class="mb-4 px-4">
            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                <unicon class="mr-2" height="22" width="22" name="dashboard"></unicon>
                Dashboard
            </jet-nav-link>
        </div>

        <div class="mb-4 px-4">
            <p class="text-sm font-semibold mb-1 text-gray-400 flex items-center">
                Autos
            </p>
            <jet-nav-link :href="route('cars.create')" :active="route().current('cars.create')">
                <unicon class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neues Auto
            </jet-nav-link>
            <jet-nav-link :href="route('cars')" :active="route().current('cars')">
                <unicon class="mr-2" height="22" width="22" name="car-sideview"></unicon>
                Alle Autos
            </jet-nav-link>
            <jet-nav-link :href="route('cars.unsold')" :active="route().current('cars.unsold')">
                <unicon class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon> 
                Meine Autos
            </jet-nav-link>
            <jet-nav-link :href="route('cars.sold')" :active="route().current('cars.sold')">
                <unicon class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Verkaufte Autos
            </jet-nav-link>
        </div>

        <div class="mb-4 px-4">
            <p class="text-sm font-semibold mb-1 text-gray-400 flex items-center">
                Kontakte
            </p>
            <jet-nav-link :href="route('contacts.create')" :active="route().current('contacts.create')">
                <unicon class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neuer Kontakt
            </jet-nav-link>
            <jet-nav-link :href="route('contacts')" :active="route().current('contacts')">
                <unicon class="mr-2" height="22" width="22" name="users-alt"></unicon>
                Alle Kontakte
            </jet-nav-link>
            <jet-nav-link :href="route('contacts.buyers')" :active="route().current('contacts.buyers')">
                <unicon class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Käufer
            </jet-nav-link>
            <jet-nav-link :href="route('contacts.sellers')" :active="route().current('contacts.sellers')">
                <unicon class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Verkäufer
            </jet-nav-link>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import JetNavLink from '@/Jetstream/NavLink'

export default {
    components: {
        JetNavLink,
    },
    computed: {
        ...mapState(['sideBarOpen'])
    }
}
</script>