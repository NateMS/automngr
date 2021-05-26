<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Meine Autos
            </h2>
        </template>
        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <simple-table :title="cars.total + ' Autos'" :data="cars" :columns="columns" :defaultSort="sort" :filters="filters" :currentRoute="currentRoute"  />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import SimpleTable from '@/Components/SimpleTable.vue'

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
            currentRoute: 'cars.unsold',
            columns: [
            {key: 'name', value: 'Name', sortable: true},
            {key: 'stammnummer', value: 'Stammummer', sortable: true},
            {key: 'initial_date', value: 'Inverkehrssetzung', sortable: true},
            {key: 'buy_contract.date', value: 'Einkaufsdatum', sortable: true},
            {key: 'buy_contract.price', value: 'Einkaufspreis', sortable: true},
            ],
        }
    },
}
</script>