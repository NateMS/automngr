<template>
    <div>
        <p v-if="title" class="font-semibold text-2xl font-medium mb-4 text-indigo-900 leading-tight">{{ title }}</p>
        <div v-if="form" class="my-6 flex justify-between items-center">
            <div class="flex items-center w-full max-w-md mr-4">
                <div class="flex w-full bg-white shadow rounded">
                    <input type="text" ref="search" v-model="form.search" autofocus="true" name="search" placeholder="Suchen..." class="relative border-gray-200 w-full px-6 py-3 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded" autocomplete="off">
                </div>
                <button @click="reset" type="button" class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-blue-200">Reset</button>
            </div>
            <a v-if="print" :href="route(currentRoute + '.print', { search: form.search, sortBy: sort.by, direction: sort.direction })" class="justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:ring-green-300 py-3">
                <unicon fill="white" class="mr-2" height="24" width="24" name="chart"></unicon>
                Excel-Export
            </a>
        </div>
        <div v-if="data.total === undefined || data.total > 0" class="bg-white shadow rounded-md sm:rounded-lg overflow-x-auto">
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
                <tr v-for="row in (this.data.data ? this.data.data : this.data)" :key="row.link" class="hover:bg-indigo-100 focus-within:bg-indigo-100">
                    <td v-for="col in columns" :key="col.key" class="border-t">
                        <inertia-link v-if="row.link" class="px-6 py-4 flex items-center" :href="row.link">
                            {{ resolve(col.key, row) }}
                        </inertia-link>
                        <span v-else-if="col.key == 'delete'" class="p-3 cursor-pointer" @click="this.$emit('delete', row.id)">
                            <unicon fill="red" height="24" width="24" name="trash-alt"></unicon>
                        </span>
                        <span v-else class="px-6 py-4 flex items-center">
                            {{ resolve(col.key, row) }}
                        </span>
                    </td>
                    <td v-if="row.link && !hideArrow" class="border-t w-px">
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
        <Paginator v-if="data.links" class="mt-6" :links="data.links" />
    </div>
</template>

<script>
import Paginator from '@/Components/Paginator';
import StandardButton from '@/Components/Buttons/StandardButton.vue';
import { pickBy, throttle, mapValues } from 'lodash';

export default {
  components: {
    Paginator,
    StandardButton,
  },
  props: {
    data: Object,
    columns: Array,
    title: String,
    currentRoute: String,
    defaultSort: Object,
    filters: Object,
    print: Boolean,
    hideArrow: Boolean,
  },
  data() {
    return {
      form: this.filters,
      sort: this.defaultSort,
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.refreshTable();
      }, 300),
    },
  },
  methods: {
    resolve(path, obj) {
      return path.split('.').reduce((prev, curr) => (prev ? prev[curr] : null), obj || self);
    },
    sortTable(col) {
      event.preventDefault();
      if (this.sort.by == col) {
        this.sort.direction = this.sort.direction == 'asc' ? 'desc' : 'asc';
      } else {
        this.sort.direction = 'asc';
      }
      this.sort.by = col;
      this.$inertia.get(this.data.path, { sortby: this.sort.by, direction: this.sort.direction }, { preserveState: true });
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    refreshTable() {
      if (this.currentRoute) {
        this.$inertia.get(this.route(this.currentRoute), pickBy(this.form), { preserveState: true });
      }
    },
    isActiveSort(col, dir) {
      return col == this.sort.by && dir == this.sort.direction;
    },
  },
};
</script>
