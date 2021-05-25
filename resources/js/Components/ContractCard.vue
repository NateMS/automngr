<template>
    <div class="py-6 grid grid-cols-12 gap-12 max-w-7xl">
        <div v-if="contract.contact" class="col-span-4 xs:col-span-12">
            <h3>{{ meta.contact }}</h3>
            <contact-card :contact="contract.contact" />
        </div>
        <div class="col-span-4 xs:col-span-12">
            <h3>{{ meta.title }}</h3>
            <div class="mt-3 p-5 bg-white shadow rounded-md font-medium">
                <div v-if="contract.date" class="font-bold">
                    {{' vom ' + contract.date }}
                </div>
                <div v-if="contract.price">
                    Preis: {{ contract.price }}
                </div>
                <div v-if="contract.insurance_type">
                    Versicherungstyp: {{ contract.insurance_type }}
                </div>
            </div>
        </div>
        <div v-if="contract.car" class="col-span-4 xs:col-span-12">
            <h3>{{ meta.car }}</h3>
            <car-card :car="contract.car" />
        </div>
        <div class="col-span-2 xs:col-span-12 py-9">
            <div class="w-full flex flex-col">
                <inertia-link :href="route('contracts.print', contract.id)" class="mb-5 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >
                    <unicon fill="white" class="mr-1" height="22" width="22" name="file-download"></unicon>
                    ausdrucken
                </inertia-link>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <!-- <simple-table title="Ankaufverträge" :data="car.buy_contracts" :columns="buyContractsColumns" /> -->
        </div>
        <div class="max-w-7xl pt-6 sm:px-6 lg:px-8">    
            <!-- <simple-table title="Verkaufverträge" :data="car.sell_contracts" :columns="sellContractsColumns" /> -->
        </div>
    </div>
</template>

<script>
import SimpleTable from '@/Components/SimpleTable.vue'
import ContactCard from '@/Components/ContactCard.vue'
import CarCard from '@/Components/CarCard.vue'

export default {
    components: {
        SimpleTable,
        ContactCard,
        CarCard,
    },
    props: {
        contract: Object,
        meta: Object,
    },
    computed: {

    },
    data() {
        return {
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