<template>
  <inertia-link :href="href" class="justify-center inline-flex items-center px-4 py-2  font-semibold text-xs text-red-300 hover:text-red-500 uppercase tracking-widest hover:font-underline transition">
    <unicon fill="currentColor" class="mr-1" height="22" width="22" name="trash-alt"></unicon>
    löschen
  </inertia-link>
</template>

<script>
import StandardButton from './StandardButton.vue';

export default {
  components: { StandardButton },
  props: {
    class: String,
    href: String,
  },
};
</script>
