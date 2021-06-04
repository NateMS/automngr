<template>
    <div>
        <div class="bg-grey overflow-hidden sm:rounded-lg">
            <div v-if="title" class="whitespace-nowrap mb-3">
                <h1 class="mb-1 font-bold text-3xl">{{ title }}</h1>
            </div>
            <div v-if="form" class="my-6 flex justify-between items-center">
                <div class="flex items-center w-full max-w-md mr-4">
                    <div class="flex w-full bg-white shadow rounded">
                        <input type="text" ref="search" v-model="form.search" autofocus="true" name="search" placeholder="Suchen..." class="relative border-gray-200 w-full px-6 py-3 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded" autocomplete="off">
                    </div>
                    <button @click="reset" type="button" class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-blue-200">Reset</button>
                </div>
            </div>
            <div v-if="data.total > 0" class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                   <tr class="text-left font-bold">
                        <th v-for="(col, index) in columns" :key="col.key" class="px-6 pt-4 pb-4" :colspan="[index == (columns.length - 1) ? 2 : 1]">
                            <a v-if="col.sortable" href="#" @click="sortTable(col.key)" class="flex place-items-center">
                                {{ col.value }}
                                <unicon v-if="isActiveSort(col.key, 'asc')" fill="#4B5563" height="22" width="22" name="arrow-up"></unicon>
                                <unicon v-if="isActiveSort(col.key, 'desc')" fill="#4B5563" height="22" width="22" name="arrow-down"></unicon>
                            </a>
                            <span v-else class="flex items-center">
                                {{ col.value }}
                            </span>
                        </th>
                    </tr>
                    <tr v-for="row in data.data" :key="row.link" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td v-for="col in columns" :key="col.key" class="border-t">
                            <inertia-link v-if="row.link" class="px-6 py-4 flex items-center" :href="row.link">
                                {{ resolve(col.key, row) }}
                            </inertia-link>
                            <span v-else class="px-6 py-4 flex items-center">
                                {{ resolve(col.key, row) }}
                            </span>
                        </td>
                        <td v-if="row.link" class="border-t w-px">
                            <inertia-link class="px-4 flex items-center" :href="row.link" tabindex="-1">
                                <unicon class="m-2" height="22" width="22" name="angle-right"></unicon>
                            </inertia-link>
                        </td>
                    </tr>
                    <tr v-if="data.total === 0">
                        <td class="border-t px-6 py-4" :colspan="columns.length">Keine Einträge gefunden</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <span class="inline-flex font-medium text-gray-500 ml-3">
                    <unicon fill="#7e8491" class="mr-2" height="24" width="24" name="meh"></unicon>
                    Keine Einträge gefunden
                </span>
            </div>
        </div>
        
        <Paginator v-if="data.links" class="mt-6" :links="data.links" />
    </div>
</template>

<script>
import Paginator from "@/Components/Paginator"
import { pickBy, throttle, mapValues } from 'lodash'

export default {
    components: {
        Paginator,
    },
    props: {
        data: Object,
        columns: Array,
        title: String,
        currentRoute: String,
        defaultSort: Object,
        filters: Object,
    },
    data() {
        return {
            form: this.filters,
            sort: this.defaultSort,
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function() {
                this.refreshTable();
            }, 300),
        },
    },
    methods: {
        resolve(path, obj) {
            return path.split('.').reduce(function(prev, curr) {
                return prev ? prev[curr] : null
            }, obj || self)
        },
        sortTable(col) {
            event.preventDefault();
            if (this.sort.by == col) {
                this.sort.direction = this.sort.direction == 'asc' ? 'desc' : 'asc';
            } else {
                this.sort.direction = 'asc';
            }
            this.sort.by = col;
            this.$inertia.get(this.data.path, {'sortby': this.sort.by, 'direction': this.sort.direction}, { preserveState: true })
        },
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        refreshTable() {
            if (this.currentRoute) {
                this.$inertia.get(this.route(this.currentRoute), pickBy(this.form), { preserveState: true })
            }
        },
        isActiveSort(col, dir) {
            return col == this.sort.by && dir == this.sort.direction;
        }
    },
}
</script>