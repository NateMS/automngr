<template>
  <layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Aktivitätslog
        </h2>
    </template>
    <div class="w-full mx-auto">
        <simple-table :title="activities.total + ' ' + (activities.total === 1 ? 'Eintrag' : 'Einträge')" :data="activities" :columns="columns" :defaultSort="sort" :filters="filters" :currentRoute="currentRoute" />
    </div>
  </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import SimpleTable from '@/Components/SimpleTable.vue';

export default {
  components: {
    Layout,
    SimpleTable,
  },
  props: {
    filters: Object,
    sort: Object,
    activities: Object,
  },
  data() {
    return {
      currentRoute: 'activity-log',
      columns: [
        { key: 'created_at', value: 'Datum' },
        { key: 'causer_name', value: 'Benutzer' },
        { key: 'description', value: 'Aktion' },
        { key: 'subject_type', value: 'Objekt' },
        { key: 'subject_id', value: 'ID' },
      ],
    };
  },
};
</script>
