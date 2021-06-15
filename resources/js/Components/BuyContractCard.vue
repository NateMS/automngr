<template>
    <contract-card :contract="contract" :meta="meta" />
</template>

<script>
import ContractCard from '@/Components/ContractCard.vue';

export default {
  components: {
    ContractCard,
  },
  props: {
    contract: Object,
  },
  data() {
    return {
      meta: {
        contact: 'Verkäufer',
      },
    };
  },
};
</script>
