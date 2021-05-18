<template>
  <layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <bread-crumb text="Kontakte" :href="route('contacts')" />
            Käufer
        </h2>
    </template>
    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <simple-table :title="contacts.total + ' Verkäufer'" :data="contacts" :columns="columns" :defaultSort="sort" :filters="filters" :currentRoute="currentRoute" />
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
    contacts: Object,
  },
  data() {
    return {
      currentRoute: 'contacts.sellers',
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
}
</script>