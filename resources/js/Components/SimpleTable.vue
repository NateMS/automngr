<template>
    <div>
        <div class="bg-grey overflow-hidden sm:rounded-lg">
            <div class="whitespace-nowrap">
                <h3 class="font-semibold text-xl m-3 text-gray-800 leading-tight">{{ title }}</h3>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                   <tr class="text-left font-bold">
                        <th v-for="col in columns" :key="col.key" class="px-6 pt-4 pb-4">{{ col.value }}</th>
                    </tr>
                    <tr v-for="row in data.data" :key="row.link" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td v-for="col in columns" :key="col.key" class="border-t">
                            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="row.link">
                                {{ row[col.key] }}
                                <unicon v-if="col.key == columns[columns.length - 1].key" class="m-2" height="22" width="22" name="angle-right"></unicon>
                            </inertia-link>
                        </td>
                    </tr>
                    <tr v-if="data.total === 0">
                        <td class="border-t px-6 py-4" colspan="4">Keine Einträge gefunden</td>
                    </tr>
                </table>
            </div>
        </div>
        <Paginator class="mt-6" :links="data.links" />
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