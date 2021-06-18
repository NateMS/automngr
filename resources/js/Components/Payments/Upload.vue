<template>
    <standard-button class="mb-3" colour="green" @click="openModal" :href="route('payments.create', contract.id)">
        <unicon fill="white" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
        Neue Einzahlung
    </standard-button>
    <payment-create-modal v-if="show_upload" :id="contract.id" :left_to_pay="contract.left_to_pay_raw" :show="showModal" @close="showModal = false" />
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
