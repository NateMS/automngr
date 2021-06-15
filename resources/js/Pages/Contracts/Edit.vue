<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                <bread-crumb :text="contract.car.name" :href="route('cars.show', contract.car.id)" />
                <bread-crumb :text="contract.type_formatted + ' vom ' + contract.date_formatted" :href="route('contracts.show', contract.id)" />
                bearbeiten
            </h2>
        </template>
        <div>
            <contract-form :data="data" :insurance_types="insurance_types" :meta="meta">
                <template #title>Vertragsangaben</template>
                <template #description>
                    {{ contract.type_formatted }} &amp; anpassen.
                </template>
            </contract-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import { ref } from 'vue';
import ContractForm from './Components/ContractForm.vue';

export default {
  components: {
    BreadCrumb,
    Layout,
    ContractForm,
  },
  props: {
    contract: Object,
    insurance_types: Array,
  },
  data() {
    return {
      currentRoute: 'contracts.edit',
      meta: {
        form_name: `EditContract${this.contract.id}`,
        route: this.route('contracts.update', this.contract.id),
        method: 'put',
        button_text: 'Änderungen speichern',
        on_success: 'Änderungen gespeichert',
      },
      data: {
        date: ref(new Date(this.contract.date)),
        price: this.contract.price,
        insurance_type: this.contract.insurance_type,
        is_sell_contract: this.contract.is_sell_contract,
      },
    };
  },
};
</script>
