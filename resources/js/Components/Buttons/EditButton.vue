<template>
    <standard-button :href="href" :class="this.class + ' mb-3'" colour="gray">
        <unicon fill="white" class="mr-1" height="22" width="22" name="pen"></unicon>
        bearbeiten
    </standard-button>
</template>

<script>
import StandardButton from './StandardButton.vue';

export default {
  components: { StandardButton },
    props: {
        class: String,
        href: String,
    },
}
</script>