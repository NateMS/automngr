<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="grid grid-cols-12 gap-x-8 gap-y-12">
            <dash-item title="Autos im Lager" :number="my_cars" :link="route('cars.unsold')" />
            <dash-item :title="'Gekauft im ' + new Date().getFullYear()" :number="bought_this_year" />
            <dash-item :title="'Verkauft im ' + new Date().getFullYear()" :number="sold_this_year" />
            <div class="lg:col-span-6 col-span-12">
                <contract-table
                    :contracts="buy_contracts"
                    :show_upload="false"
                    :type="0"
                    title="Neueste Einkäufe"
                />
            </div>
            <div class="lg:col-span-6 col-span-12">
                <contract-table
                    :contracts="sell_contracts"
                    :show_upload="false"
                    :type="1"
                    title="Neueste Verkäufe"
                />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import DashItem from '@/Components/Dashboard/DashItem.vue';
import ContractTable from '@/Components/Contracts/ContractTable.vue';

export default {
  components: {
    Layout,
    ContractTable,
    DashItem,
  },
  props: {
    buy_contracts: Object,
    sell_contracts: Object,
    sold_this_year: Number,
    bought_this_year: Number,
    my_cars: Number,
  },
};
</script>
