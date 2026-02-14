<template>
    <button type="button" @click="showModal = true" class="justify-center inline-flex items-center sm:px-4 sm:py-2 px-2 py-1 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:ring-green-300 transition">
        <unicon fill="white" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
        Neue Einzahlung
    </button>
    <payment-create-modal v-if="show_upload" :id="contract.id" :left_to_pay="contract.left_to_pay_raw" :show-modal="showModal" @close="showModal = false" />
</template>

<script>
import PaymentCreateModal from '@/Components/Payments/CreateModal.vue';
import StandardButton from '@/Components/Buttons/StandardButton.vue';

export default {
  components: {
    PaymentCreateModal,
    StandardButton,
  },
  props: {
    contract: Object,
    show_upload: Boolean,
  },
  data() {
    return {
      showModal: false,
    };
  },
  methods: {
    openModal(e) {
      e.preventDefault();
      this.showModal = true;
    },
  },
};
</script>
