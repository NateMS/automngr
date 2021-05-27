<template>
    <standard-button :href="href" :class="this.class" colour="gray">
        <unicon fill="white" class="mr-1" height="22" width="22" name="file-download"></unicon>
        drucken
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