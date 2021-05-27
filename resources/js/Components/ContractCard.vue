<template>
    <div class="py-3 grid grid-cols-12 gap-3 w-full">

        <div v-if="contract.contact" class="col-span-6 xs:col-span-12">
            <h3 class="mb-3">{{ meta.contact }}</h3>
            <contact-card :contact="contract.contact" />
        </div>
        <div v-if="contract.car" class="col-span-6 xs:col-span-12">
            <h3 class="mb-3">Auto</h3>
            <car-card :car="contract.car" />
        </div>
        <div class="col-span-6 xs:col-span-12 h-full relative">
            <h3>Vertragsinformationen</h3>
            <div class="mt-3 p-5 bg-white shadow rounded-md font-medium">
                <div v-if="contract.date">
                    Datum: {{ contract.date }}
                </div>
                <div v-if="contract.is_sell_contract && contract.insurance_type">
                    Versicherung: {{ contract.insurance_type }}
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
                    <print-button class="mb-0" :href="route('contracts.print', contract.id)" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SimpleTable from '@/Components/SimpleTable.vue'
import ContactCard from '@/Components/ContactCard.vue'
import CarCard from '@/Components/CarCard.vue'
import PrintButton from './Buttons/PrintButton.vue'

export default {
    components: {
        SimpleTable,
        ContactCard,
        CarCard,
        PrintButton,
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