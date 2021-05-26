<template>
    <div class="py-6 grid grid-cols-12 gap-3 w-full">

        <div v-if="contract.contact" class="col-span-6 xs:col-span-12">
            <h3>{{ meta.contact }}</h3>
            <contact-card :contact="contract.contact" />
        </div>
        <div v-if="contract.car" class="col-span-6 xs:col-span-12">
            <h3>{{ meta.car }}</h3>
            <car-card :car="contract.car" />
        </div>
        <div class="col-span-6 xs:col-span-12 h-full relative">
            <h3>Vertragsinformationen</h3>
            <div class="mt-3 p-5 bg-white shadow rounded-md font-medium">
                <div v-if="contract.date">
                    Datum: {{ contract.date }}
                </div>
                <div v-if="contract.insurance_type">
                    Versicherungstyp: {{ contract.insurance_type }}
                </div>
                <div v-if="contract.price" class="pt-8 font-bold text-2xl">
                    {{ contract.price }}
                </div>
                <div v-if="contract.link" class="pt-3 mt-7 border-t">
                    <inertia-link :href="contract.link" class="pt-1 pb-1 flex items-center">
                        <unicon class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                        Zum Vertrag
                    </inertia-link>
                </div>
            </div>
            <div class="absolute left-0 right-0 bottom-0">
                <div class="w-full flex flex-col">
                    <a :href="route('contracts.print', contract.id)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >
                        <unicon fill="white" class="mr-1" height="22" width="22" name="file-download"></unicon>
                        drucken
                    </a>
                </div>
            </div>
            
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