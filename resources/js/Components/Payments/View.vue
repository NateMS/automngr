<template>
    <span class="w-full inline-flex items-end justify-between mb-3">
        <h3>Einzahlungen</h3>
        <standard-button class="" colour="green" @click="openModal" :href="route('payments.create', id)">
            <unicon fill="white" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
            Neue Einzahlung
        </standard-button>
    </span>
    <div class="w-full mx-auto">
        <simple-table :data="payments" :columns="columns" @delete="deletePayment" />
    </div>
    <payment-create-modal :id="id" :show="showModal" @close="showModal = false" />
</template>

<script>
import SimpleTable from '@/Components/SimpleTable.vue'
import PaymentCreateModal from '@/Components/Payments/CreateModal.vue'
import StandardButton from '@/Components/Buttons/StandardButton.vue'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    components: {
        SimpleTable,
        PaymentCreateModal,
        StandardButton,
    },
    props: {
        payments: Object,
        id: Number,
        show_upload: Boolean,
    },
    data() {
        return {
            showModal: false,
            columns: [
                {key: 'date', value: 'Datum', sortable: false},
                {key: 'amount', value: 'Betrag', sortable: false},
                {key: 'type', value: 'Bezahlart', sortable: false},
                {key: 'delete', value: '', sortable: false},
            ],
        }
    },
    methods: {
        openModal(e) {
            e.preventDefault();
            this.showModal = true;
        },
        deletePayment(id) {
            let form = useForm(`deletePayment${id}`, {id: id});
            form.delete(route('payments.destroy', this.id), {
                preserveScroll: true,
                onSuccess: () => form.reset(), 
            });
        },
    },
}
</script>