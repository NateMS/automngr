<template>
  <layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kontakte
        </h2>
    </template>
    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
              <div class="mb-6 flex justify-between items-center">
                <!-- <search-filter ref="search" v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter> -->
                <input type="text" ref="search" v-model="form.search" autofocus="true" name="search" placeholder="Suchen..." class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" autocomplete="off">
            </div>
            <simple-table :title="contacts.total + ' Kontakte'" :data="contacts" :columns="columns" :defaultSort="sort" />
        </div>
    </div>
  </layout>
</template>

<script>
import { pickBy, throttle, mapValues } from 'lodash'
import Layout from '@/Layouts/Layout'
import SimpleTable from '@/Components/SimpleTable.vue'
import SearchFilter from '@/Components/SearchFilter'
import JetButton from '@/Jetstream/Button'

export default {
  components: {
    SearchFilter,
    JetButton,
    Layout,
    SimpleTable,
  },
  props: {
    filters: Object,
    sort: Object,
    contacts: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
      columns: [
        {key: 'name', value: 'Name', sortable: true},
        {key: 'company', value: 'Firma', sortable: true},
        {key: 'address', value: 'Adresse', sortable: true},
        {key: 'fullCity', value: 'Ort', sortable: true},
        {key: 'email', value: 'E-Mail', sortable: true},
        {key: 'phone', value: 'Telefon'},
      ],
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get(this.route('contacts'), pickBy(this.form), { preserveState: false })
      }, 300),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>