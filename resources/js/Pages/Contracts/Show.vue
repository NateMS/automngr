<template>
    <show-page>
        <template #header>
            {{ contract.type_formatted }} vom {{ contract.date }}
        </template>
        <template #info>
                    <small-title title="Vertragsinformationen" class="mb-3" />
                    <div class="grid  grid-cols-12 gap-2 p-5 bg-white shadow rounded-md font-medium">
                        <div class="col-span-4">
                            Datum
                        </div>
                        <div class="col-span-8">
                            {{ contract.date }}
                        </div>
                        <div class="col-span-12 sm:col-span-4 pt-3 sm:pt-0">
                            {{ contactTitle }}
                        </div>
                        <div class="col-span-12 sm:col-span-8">
                            <inertia-link :href="contract.contact.link" class="font-bold flex items-center hover:text-indigo-600">
                                <unicon fill="currentColor" class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                                {{ contract.contact.full_title }}
                            </inertia-link>
                        </div>
                        <div class="col-span-12 sm:col-span-4 pt-3 sm:pt-0">
                           Auto
                        </div>
                        <div class="col-span-12 sm:col-span-8 pb-3 sm:pb-0">
                            <inertia-link :href="contract.car.link" class="font-bold flex items-center hover:text-indigo-600">
                                <unicon fill="currentColor" class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                                {{ contract.car.name_with_year }}
                            </inertia-link>
                        </div>
                        <div class="col-span-4">
                            Lieferdatum
                        </div>
                        <div class="col-span-8">
                            {{ contract.delivery_date }}
                        </div>
                        <div v-if="contract.is_sell_contract && contract.insurance_type" class="col-span-4">
                            Versicherung
                        </div>
                        <div v-if="contract.is_sell_contract && contract.insurance_type" class="col-span-8">
                            {{ contract.insurance_type }}
                        </div>
                        <div class="col-span-4">
                            Betrag
                        </div>
                        <div class="col-span-8 font-bold">
                            {{ contract.price }}
                        </div>
                        <div class="col-span-4">
                            Bezahlt
                        </div>
                        <div class="col-span-8">
                            {{ contract.paid }}
                        </div>
                        <div class="col-span-4">
                            Offener Betrag
                        </div>
                        <div class="col-span-8">
                            {{ contract.left_to_pay }}
                        </div>
                        <div v-if="contract.notes" class="mt-3 col-span-12">
                            <p class="font-bold">Bemerkungen</p>
                            {{ contract.notes }}
                        </div>
                    </div>
        </template>
        <template #actions>
            <edit-button v-if="!contract.deleted_at" :href="route('contracts.edit', contract.id)" />
            <print-button :href="route('contracts.print', contract.id)" />
            <delete-button v-if="!contract.deleted_at" :href="route('contracts.destroy', contract.id)" />
            <restore-button v-if="contract.deleted_at" :href="route('contracts.restore', contract.id)" />
            <div v-if="contract.deleted_at">
                gelöscht: {{ contract.deleted_at }}
            </div>
        </template>
        <template #more>
            <span class="inline-flex items-end w-full justify-between mb-3">
                <small-title title="Einzahlungen" />
                <payments-upload v-if="!contract.deleted_at" :show_upload="!contract.deleted_at" :contract="contract" />
            </span>
            <payments-view :payments="contract.payments" :contract="contract" />
            <documents-view :initial_documents="contract.documents" :id="contract.id" :show_upload="!contract.deleted_at" />
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import RestoreButton from '@/Components/Buttons/RestoreButton.vue';
import CarCard from '@/Components/CarCard.vue';
import PrintButton from '@/Components/Buttons/PrintButton.vue';
import ContactCard from '@/Components/ContactCard.vue';
import EditButton from '@/Components/Buttons/EditButton.vue';
import DocumentsView from '@/Components/Documents/View.vue';
import PaymentsView from '@/Components/Payments/View.vue';
import PaymentsUpload from '@/Components/Payments/Upload.vue';
import SmallTitle from '../../Components/SmallTitle.vue';

export default {
  components: {
    BreadCrumb,
    ShowPage,
    CarCard,
    DeleteButton,
    RestoreButton,
    PrintButton,
    ContactCard,
    EditButton,
    DocumentsView,
    PaymentsView,
    PaymentsUpload,
    SmallTitle,
  },
  props: {
    contract: Object,
  },
  computed: {
    contactTitle() {
      return this.contract.is_sell_contract ? 'Käufer' : 'Verkäufer';
    },
  },
  data() {
    return {
      currentRoute: 'contracts.show',
      buyContractsColumns: [
        { key: 'contact', value: 'Verkäufer' },
        { key: 'date', value: 'Kaufdatum' },
        { key: 'price', value: 'Kaufpreis' },
      ],
      sellContractsColumns: [
        { key: 'contact', value: 'Käufer' },
        { key: 'date', value: 'Verkaufsdatum' },
        { key: 'price', value: 'Verkaufspreis' },
        { key: 'insurance_type', value: 'Versicherungstyp' },
      ],
    };
  },
};
</script>
