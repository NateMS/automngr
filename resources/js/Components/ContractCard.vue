<template>
    <div class="py-3 grid grid-cols-12 gap-3 w-full">
        <div v-if="contract.contact" class="sm:col-span-6 col-span-12">
            <h3 class="mb-3">{{ meta.contact }}</h3>
            <contact-card :contact="contract.contact" />
        </div>
        <div v-if="contract.car" class="sm:col-span-8 col-span-12">
            <h3 class="mb-3">Auto</h3>
            <car-card hide-empty="true" :car="contract.car" />
        </div>
        <div :class="contractClasses">
            <h3>Vertragsinformationen</h3>
            <div class="mt-3 p-5 bg-white shadow rounded-md font-medium">
                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-2">
                        Datum
                    </div>
                    <div class="col-span-2">
                        {{ contract.date }}
                    </div>
                    <div class="col-span-2">
                        Lieferdatum
                    </div>
                    <div class="col-span-2">
                        {{ contract.delivery_date }}
                    </div>
                    <div class="col-span-2" v-if="contract.is_sell_contract && contract.insurance_type">
                        Versicherung
                    </div>
                    <div class="col-span-2" v-if="contract.is_sell_contract && contract.insurance_type">
                        {{ contract.insurance_type }}
                    </div>
                    <div class="col-span-2">
                        Betrag
                    </div>
                    <div class="col-span-2 font-bold">
                        {{ contract.price }}
                    </div>
                    <div class="col-span-2">
                        Bezahlt
                    </div>
                    <div class="col-span-2">
                        {{ contract.paid }}
                    </div>
                    <div class="col-span-2">
                        Offener Betrag
                    </div>
                    <div class="col-span-2">
                        {{ contract.left_to_pay }}
                    </div>
                    <div v-if="contract.notes" class="col-span-2">
                        Bemerkung
                    </div>
                    <div v-if="contract.notes" class="col-span-2">
                        {{ contract.notes }}
                    </div>
                </div>
                <div v-if="contract.link" class="pt-3 mt-3 border-t">
                    <inertia-link :href="contract.link" class="pt-1 pb-1 flex items-center hover:text-indigo-600">
                        <unicon fill="currentColor" class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                        Zum Vertrag
                    </inertia-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SimpleTable from '@/Components/SimpleTable.vue';
import ContactCard from '@/Components/ContactCard.vue';
import CarCard from '@/Components/CarCard.vue';
import PrintButton from './Buttons/PrintButton.vue';

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
    contractClasses() {
      return `col-span-12 h-full relative sm:col-span-${this.contract.car ? '4' : '6'}`;
    },
  },
  data() {
    return {
      sellContractsColumns: [
        { key: 'buyer', value: 'Käufer' },
        { key: 'date', value: 'Verkaufsdatum' },
        { key: 'price', value: 'Verkaufspreis' },
        { key: 'insurance_type', value: 'Versicherungstyp' },
      ],
    };
  },
};
</script>
