<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                {{ car.name }}
            </h2>
        </template>
        <div class="py-6 grid grid-cols-12 gap-12 max-w-7xl sm:px-6 lg:px-8">
                <div class="col-span-6 xs:col-span-12">
                    <car-card :car="car" />
                </div>
                <div class="col-span-2 xs:col-span-12">
                    <div class="w-full flex flex-col">
                        <inertia-link :href="route('cars.edit', car.id)" class="mb-5 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >
                            <unicon fill="white" class="mr-1" height="22" width="22" name="pen"></unicon>
                            bearbeiten
                        </inertia-link>
                        <inertia-link :href="route('cars.edit', car.id)" class="mb-5 inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition" >
                            <unicon fill="white" class="mr-1" height="22" width="22" name="trash-alt"></unicon>
                            löschen
                        </inertia-link>
                    </div>
                   
                </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <simple-table title="Ankaufverträge" :data="car.buy_contracts" :columns="buyContractsColumns" />
            </div>
            <div class="max-w-7xl pt-6 sm:px-6 lg:px-8">    
                <simple-table title="Verkaufverträge" :data="car.sell_contracts" :columns="sellContractsColumns" />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import SimpleTable from '@/Components/SimpleTable.vue'
import CarCard from './Components/CarCard.vue'

export default {
    components: {
        BreadCrumb,
        Layout,
        SimpleTable,
        CarCard,
    },
    props: {
        car: Object,
    },
    computed: {

    },
    data() {
        return {
            currentRoute: 'cars.show',
            buyContractsColumns: [
                {key: 'seller', value: 'Verkäufer'},
                {key: 'date', value: 'Kaufdatum'},
                {key: 'price', value: 'Kaufpreis'},
            ],
            sellContractsColumns: [
                {key: 'buyer', value: 'Käufer'},
                {key: 'date', value: 'Verkaufsdatum'},
                {key: 'price', value: 'Verkaufspreis'},
                {key: 'insurance_type', value: 'Versicherungstyp'},
            ],
        }
    },
}
</script>