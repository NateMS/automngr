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
            <div class="bg-grey overflow-hidden sm:rounded-lg">
                <div class="whitespace-nowrap">
                  <h3 class="font-semibold text-xl m-3 text-gray-800 leading-tight">{{ contacts.total }} Kontakte</h3>
                </div>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                  <table class="w-full whitespace-nowrap">
                      <tr class="text-left font-bold">
                        <th class="px-6 pt-4 pb-4">Name</th>
                        <th class="px-6 pt-4 pb-4">Firma</th>
                        <th class="px-6 pt-4 pb-4">Ort</th>
                        <th class="px-6 pt-4 pb-4" colspan="2">Telefon</th>
                      </tr>
                      <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                          <td class="border-t">
                              <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('contacts.edit', contact.id)">
                                  {{ contact.name }}
                              </inertia-link>
                          </td>
                          <td class="border-t">
                              <inertia-link class="px-6 py-4 flex items-center" :href="route('contacts.edit', contact.id)" tabindex="-1">
                              {{ contact.company}}
                              </inertia-link>
                          </td>
                          <td class="border-t">
                              <inertia-link class="px-6 py-4 flex items-center" :href="route('contacts.edit', contact.id)" tabindex="-1">
                              {{ contact.fullCity }}
                              </inertia-link>
                          </td>
                          <td class="border-t">
                              <inertia-link class="px-6 py-4 flex items-center" :href="route('contacts.edit', contact.id)" tabindex="-1">
                              {{ contact.phone }}
                              </inertia-link>
                          </td>
                          <td class="border-t w-px">
                              <inertia-link class="px-4 flex items-center" :href="route('contacts.edit', contact.id)" tabindex="-1">
                              <unicon class="m-2" height="22" width="22" name="angle-right"></unicon>
                              </inertia-link>
                          </td>
                      </tr>
                      <tr v-if="contacts.length === 0">
                        <td class="border-t px-6 py-4" colspan="4">Keine Kontakte gefunden</td>
                      </tr>
                  </table>
                </div>
            </div>
            <Paginator class="mt-6" :links="contacts.links" />
        </div>
    </div>
  </app-layout>
</template>

<script>
import { pickBy, throttle, mapValues } from 'lodash'
import AppLayout from '@/Layouts/AppLayout'
import Paginator from "@/Components/Paginator"
import SearchFilter from '@/Components/SearchFilter'
import JetButton from '@/Jetstream/Button'

export default {
  components: {
    Paginator,
    SearchFilter,
    JetButton,
    AppLayout,
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