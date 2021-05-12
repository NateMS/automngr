<template>
    <div>
        <div class="bg-grey overflow-hidden sm:rounded-lg">
            <div v-if="title" class="whitespace-nowrap">
                <h3 class="font-semibold text-xl m-3 text-gray-800 leading-tight">{{ title }}</h3>
            </div>
            <div v-if="data.total > 0" class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                   <tr class="text-left font-bold">
                        <th v-for="(col, index) in columns" :key="col.key" class="px-6 pt-4 pb-4" :colspan="[index == (columns.length - 1) ? 2 : 1]">{{ col.value }}</th>
                    </tr>
                    <tr v-for="row in data.data" :key="row.link" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td v-for="col in columns" :key="col.key" class="border-t">
                            <inertia-link v-if="row.link" class="px-6 py-4 flex items-center focus:text-indigo-500" :href="row.link">
                                {{ row[col.key] }}
                            </inertia-link>
                            <span v-else class="px-6 py-4 flex items-center focus:text-indigo-500">
                                {{ row[col.key] }}
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

export default {
  components: {
    Paginator,
  },
  props: {
    data: Object,
    columns: Array,
    title: String,
  },
}
</script>