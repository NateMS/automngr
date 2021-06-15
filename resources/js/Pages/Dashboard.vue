<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="grid grid-cols-12 gap-x-8 gap-y-12">
            <dash-item title="Meine Autos" :number="my_cars" :link="route('cars.unsold')" />
            <dash-item :title="'Gekauft im ' + new Date().getFullYear()" :number="bought_this_year" />
            <dash-item :title="'Verkauft im ' + new Date().getFullYear()" :number="sold_this_year" />
            <div class="col-span-6 xs:col-span-12">
                    <p class="font-semibold p-4 pl-6 shadow bg-white rounded-md sm:rounded-lg text-xl font-medium mb-3 text-gray-800 leading-tight">Letzte Einkäufe</p>
                    <simple-table class="shadow rounded-md sm:rounded-lg" :data="buy_contracts" :columns="buyContractColumns" :currentRoute="currentRoute" :hideArrow="true" />
            </div>
            <div class="col-span-6 xs:col-span-12">
                <div class="pt-5 bg-white shadow rounded-md sm:rounded-lg">
                    <p class="font-semibold text-xl font-medium ml-6 mb-2 text-gray-800 leading-tight">Letzte Verkäufe</p>
                    <simple-table :data="sell_contracts" :columns="sellContractColumns" :currentRoute="currentRoute" :hideArrow="true" />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from '@/Layouts/Layout'
    import SimpleTable from '@/Components/SimpleTable.vue'
    import DashItem from '@/Components/Dashboard/DashItem.vue'

    export default {
        components: {
            Layout,
            SimpleTable,
            DashItem,
        },
        props: {
            buy_contracts: Object,
            sell_contracts: Object,
            sold_this_year: Number,
            bought_this_year: Number,
            my_cars: Number,
        },
        data() {
            return {
                currentRoute: 'cars',
                buyContractColumns: [
                    {key: 'date', value: 'Datum', sortable: false},
                    // {key: 'car', value: 'Auto', sortable: false},
                    {key: 'contact', value: 'Verkäufer', sortable: false},
                    {key: 'price', value: 'Einkaufspreis', sortable: false},
                ],
                sellContractColumns: [
                    {key: 'date', value: 'Datum', sortable: false},
                    // {key: 'car', value: 'Auto', sortable: false},
                    {key: 'contact', value: 'Käufer', sortable: false},
                    {key: 'price', value: 'Verkaufspreis', sortable: false},
                ],
            }
        },
    }
</script>
