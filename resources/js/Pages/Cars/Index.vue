<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Alle Autos
            </h2>
        </template>
        <div class="w-full mx-auto">
            <simple-table :title="cars.total + ' ' + (cars.total === 1 ? 'Auto' : 'Autos')" :data="cars" :columns="columns" :defaultSort="sort" :filters="filters" :currentRoute="currentRoute" :print="true" />
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import SimpleTable from '@/Components/SimpleTable.vue';

export default {
  components: {
    BreadCrumb,
    Layout,
    SimpleTable,
  },
  props: {
    filters: Object,
    sort: Object,
    cars: Object,
  },
  data() {
    return {
      currentRoute: 'cars',
      columns: [
        { key: 'name', value: 'Marke & Modell', sortable: true },
        { key: 'stammnummer', value: 'Stammnummer', sortable: true },
        { key: 'buy_contract.date', value: 'Einkaufsdatum', sortable: true },
        { key: 'buy_contract.price', value: 'Einkaufspreis', sortable: true },
        { key: 'sell_contract.date', value: 'Verkaufssdatum', sortable: true },
        { key: 'sell_contract.price', value: 'Verkaufspreis', sortable: true },
      ],
    };
  },
};
</script>
