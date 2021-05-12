<template>
  <app-layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kontakte
        </h2>
    </template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="mb-6 flex justify-between items-center">
                <!-- <search-filter ref="search" v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter> -->
                <input type="text" ref="search" v-model="form.search" autofocus="true" name="search" placeholder="Suchen..." class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" autocomplete="off">
                <jet-button class="ml-4" @click="createContact">
                    Kontakt erstellen
                </jet-button>
            </div>
            <simple-table :title="contacts.total + ' Kontakte'" :data="contacts" :columns="columns" />
        </div>
    </div>
  </app-layout>
</template>

<script>
import { pickBy, throttle, mapValues } from 'lodash'
import AppLayout from '@/Layouts/AppLayout'
import Paginator from "@/Components/Paginator"
import SimpleTable from '@/Components/SimpleTable.vue'
import SearchFilter from '@/Components/SearchFilter'
import JetButton from '@/Jetstream/Button'

export default {
  components: {
    Paginator,
    SearchFilter,
    JetButton,
    AppLayout,
    SimpleTable,
  },
  props: {
    filters: Object,
    contacts: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
      columns: [
        {key: 'name', value: 'Name'},
        {key: 'company', value: 'Firma'},
        {key: 'fullCity', value: 'Ort'},
        {key: 'phone', value: 'Telefon'},
      ],
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get(this.route('contacts'), pickBy(this.form), { preserveState: false })
        // this.$refs.search.focus();
      }, 300),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    createContact() {
        this.$inertia.visit(route('contacts.create'), { method: 'get' })
    },
  },
}
</script>