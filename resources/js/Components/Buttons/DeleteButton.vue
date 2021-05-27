<template>
    <standard-button :href="href" :class="this.class + ' mb-3'" colour="red">
        <unicon fill="white" class="mr-1" height="22" width="22" name="trash-alt"></unicon>
        löschen
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