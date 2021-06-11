<template>
    <show-page>
        <template #header>
            <bread-crumb text="Autos" :href="route('cars')" />
            <bread-crumb :text="contract.car.name" :href="route('cars.show', contract.car.id)" />
            {{ contractType}} vom {{ contract.date }}
        </template>
        <template #info>
            <div class="p-5 bg-white shadow rounded-md font-medium">
                <div class="font-bold pb-1 mb-1 text-2xl border-b">
                    {{ contractType}} vom {{ contract.date }}
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
            <div class="col-span-12">
                <h3 class="mb-3">Dokumente</h3>
                <div class="w-full grid grid-cols-12 xs:grid-cols-4 gap-3">
                    <template v-for="document in documents" :key="document.id">
                        <document-item :document="document" />
                    </template>
                    <document-upload :contract="contract" :documents="documents" />
                </div>
            </div>
            <div class="col-span-6 xs:col-span-12">
                <h3 class="mb-3">Auto</h3>
                <car-card :car="contract.car" />
            </div>
            <div class="col-span-5 xs:col-span-12">
                <h3 class="mb-3">{{ contactTitle }}</h3>
                <contact-card :contact="contract.contact" />
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
import DocumentItem from '@/Components/Documents/Item.vue'
import DocumentUpload from '@/Components/Documents/Upload.vue'

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
        DocumentItem,
        DocumentUpload,
    },
    props: {
        contract: Object,
    },
    computed: {
        contractType: function() {
            return this.contract.type == 0 ? 'Ankaufsvertrag' : 'Verkaufsvertrag';
        },
        contactTitle: function() {
            return this.contract.type == 0 ? 'Verkäufer' : 'Käufer';
        }
    },
    data() {
        return {
            currentRoute: 'contracts.show',
            documents: this.contract.documents,
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