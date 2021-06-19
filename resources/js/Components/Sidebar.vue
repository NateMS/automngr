<template>
    <div class="w-1/2 md:w-1/3 lg:w-64 fixed md:top-0 md:left-0 h-screen lg:block bg-indigo-800 z-30" :class="sideBarOpen ? '' : 'hidden'" id="main-nav">

        <inertia-link :href="route('dashboard')" class="w-full h-20 bg-indigo-900 flex justify-center items-center mb-8 text-indigo-100 font-semibold text-2xl hover:text-indigo-300 transition">
            Your SwissCar
        </inertia-link>

        <div class="mb-4 px-4">
            <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="dashboard"></unicon>
                Dashboard
            </jet-nav-link>
            <jet-nav-link :href="route('reports')" :active="route().current('reports')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="chart"></unicon>
                Berichte
            </jet-nav-link>
        </div>

        <div class="mb-4 px-4">
            <p class="text-sm font-semibold mb-1 text-indigo-100 flex items-center">
                Verträge
            </p>
            <jet-nav-link :href="route('contracts.create', {type: 0})" :active="onBasicContractCreate && type == '0'">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neuer Einkauf
            </jet-nav-link>
            <jet-nav-link :href="route('contracts.create', {type: 1})" :active="onBasicContractCreate && type == '1'">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neuer Verkauf
            </jet-nav-link>
        </div>

        <div class="mb-4 px-4">
            <p class="text-sm font-semibold mb-1 text-indigo-100 flex items-center">
                Autos
            </p>
            <jet-nav-link :href="route('cars.create')" :active="route().current('cars.create')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neues Auto
            </jet-nav-link>
            <jet-nav-link :href="route('cars')" :active="route().current('cars')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="car-sideview"></unicon>
                Alle Autos
            </jet-nav-link>
            <jet-nav-link :href="route('cars.unsold')" :active="route().current('cars.unsold')">
                <unicon fill="currentColor" class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Meine Autos
            </jet-nav-link>
            <jet-nav-link :href="route('cars.sold')" :active="route().current('cars.sold')">
                <unicon fill="currentColor" class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Verkaufte Autos
            </jet-nav-link>
        </div>

        <div class="mb-4 px-4">
            <p class="text-sm font-semibold mb-1 text-indigo-100 flex items-center">
                Kontakte
            </p>
            <jet-nav-link :href="route('contacts.create')" :active="route().current('contacts.create')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="plus-circle"></unicon>
                Neuer Kontakt
            </jet-nav-link>
            <jet-nav-link :href="route('contacts')" :active="route().current('contacts')">
                <unicon fill="currentColor" class="mr-2" height="22" width="22" name="users-alt"></unicon>
                Alle Kontakte
            </jet-nav-link>
            <jet-nav-link :href="route('contacts.buyers')" :active="route().current('contacts.buyers')">
                <unicon fill="currentColor" class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Käufer
            </jet-nav-link>
            <jet-nav-link :href="route('contacts.sellers')" :active="route().current('contacts.sellers')">
                <unicon fill="currentColor" class="mr-2 ml-3" height="22" width="22" name="angle-right"></unicon>
                Verkäufer
            </jet-nav-link>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import JetNavLink from '@/Jetstream/NavLink';

export default {
  components: {
    JetNavLink,
  },
  computed: {
    ...mapState(['sideBarOpen']),
    type() {
        let params = new URLSearchParams(window.location.search);
        return params.get('type');
    },
    onBasicContractCreate() {
        if (!route().current('contracts.create')) {
            return false;
        }

        let params = new URLSearchParams(window.location.search);
        return !(params.get('car') || params.get('contact'));
    },
  },
};
</script>
