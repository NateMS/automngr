<template>
    <div class="w-full mx-auto">
        <simple-table :data="payments" :columns="columns" @delete="deletePayment" />
        <p class="py-5 text-xl">Total <span class="font-bold ml-5">{{ contract.paid }}</span> / {{ contract.price }}</p>
    </div>
</template>

<script>
import SimpleTable from '@/Components/SimpleTable.vue';
import { useForm } from '@inertiajs/inertia-vue3';


export default {
  components: {
    SimpleTable,
  },
  props: {
    contract: Object,
    payments: Object,
  },
  data() {
    return {
      columns: [
        { key: 'date', value: 'Datum', sortable: false },
        { key: 'amount', value: 'Betrag', sortable: false },
        { key: 'type', value: 'Bezahlart', sortable: false },
        { key: 'print', value: 'Quittung', sortable: false },
        { key: 'delete', value: '', sortable: false },
      ],
    };
  },
  methods: {
    deletePayment(id) {
      const form = useForm(`deletePayment${id}`, { id });
      form.delete(route('payments.destroy', this.contract.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
      });
    },
  },
};
</script>
