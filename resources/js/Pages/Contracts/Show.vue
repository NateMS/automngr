<template>
    <show-page>
        <template #header>
            <bread-crumb text="Autos" :href="route('cars')" />
            <bread-crumb :text="contract.car.name" :href="route('cars.show', contract.car.id)" />
            {{ contract.type_formatted }} vom {{ contract.date }}
        </template>
        <template #info>
            <div class="p-5 bg-white shadow rounded-md font-medium">
                <div class="font-bold pb-1 mb-1 text-2xl border-b">
                    {{ contract.type_formatted }} vom {{ contract.date }}
                </div>
                <div class="grid grid-cols-4 gap-2 w-full">
                    <div class="col-span-1 xs:col-span-2">
                        Datum
                    </div>
                    <div class="col-span-3 xs:col-span-2">
                        {{ contract.date }}
                    </div>
                    <div v-if="contract.is_sell_contract && contract.insurance_type" class="col-span-1 xs:col-span-2">
                        Versicherung
                    </div>
                    <div v-if="contract.is_sell_contract && contract.insurance_type" class="col-span-3 xs:col-span-2">
                        {{ contract.insurance_type }}
                    </div>
                    <div class="col-span-1 xs:col-span-2">
                        Betrag
                    </div>
                    <div class="col-span-3 xs:col-span-2 font-bold">
                        {{ contract.price }}
                    </div>
                    <div class="col-span-1 xs:col-span-2">
                        Bezahlt
                    </div>
                    <div class="col-span-3 xs:col-span-2">
                        {{ contract.paid }}
                    </div>
                    <div class="col-span-1 xs:col-span-2">
                        Offener Betrag
                    </div>
                    <div class="col-span-3 xs:col-span-2">
                        {{ contract.left_to_pay }}
                    </div>
                </div>
            </div>
        </template>
        <template #actions>
            <edit-button v-if="!contract.deleted_at" :href="route('contracts.edit', contract.id)" />
            <print-button class="mb-3" :href="route('contracts.print', contract.id)" />
            <delete-button v-if="!contract.deleted_at" :href="route('contracts.destroy', contract.id)" />
            <restore-button v-if="contract.deleted_at" :href="route('contracts.restore', contract.id)" />
            <div v-if="contract.deleted_at">
                gelöscht: {{ contract.deleted_at }}
            </div>
        </template>
        <template #more>
            <div class="col-span-6 xs:col-span-12">
                <h3 class="mb-3">Auto</h3>
                <car-card :car="contract.car" />
            </div>
            <div class="col-span-5 xs:col-span-12">
                <h3 class="mb-3">{{ contactTitle }}</h3>
                <contact-card :contact="contract.contact" />
            </div>
            <div class="col-span-6 xs:col-span-12 mt-4">
                <documents-view :initial_documents="contract.documents" :id="contract.id" :show_upload="!contract.deleted_at" />
            </div>
            <div class="col-span-5 xs:col-span-12">
                <payments-view :payments="contract.payments" :contract="contract" />
            </div>
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import SimpleTable from '@/Components/SimpleTable.vue'
import DeleteButton from '@/Components/Buttons/DeleteButton.vue'
import RestoreButton from '@/Components/Buttons/RestoreButton.vue'
import CarCard from '@/Components/CarCard.vue'
import PrintButton from '@/Components/Buttons/PrintButton.vue'
import ContactCard from '@/Components/ContactCard.vue'
import EditButton from '@/Components/Buttons/EditButton.vue'
import DocumentsView from '@/Components/Documents/View.vue'
import PaymentsView from '@/Components/Payments/View.vue'


export default {
    components: {
        BreadCrumb,
        ShowPage,
        SimpleTable,
        CarCard,
        DeleteButton,
        RestoreButton,
        PrintButton,
        ContactCard,
        EditButton,
        CarCard,
        DocumentsView,
        PaymentsView,
    },
    props: {
        contract: Object,
    },
    computed: {
        contactTitle: function() {
            return this.contract.is_sell_contract ? 'Käufer' : 'Verkäufer';
        }
    },
    data() {
        return {
            currentRoute: 'contracts.show', 
            buyContractsColumns: [
                {key: 'contact', value: 'Verkäufer'},
                {key: 'date', value: 'Kaufdatum'},
                {key: 'price', value: 'Kaufpreis'},
            ],
            sellContractsColumns: [
                {key: 'contact', value: 'Käufer'},
                {key: 'date', value: 'Verkaufsdatum'},
                {key: 'price', value: 'Verkaufspreis'},
                {key: 'insurance_type', value: 'Versicherungstyp'},
            ],
        }
    },
}
</script>