<template>
    <inertia-link :href="href" :class="allClasses">
        <slot></slot>
    </inertia-link>
</template>

<script>

export default {
  props: {
    class: String,
    href: String,
    colour: String,
  },
  computed: {
    allClasses() {
      let classes = 'justify-center inline-flex items-center sm:px-4 sm:py-2 px-2 py-1 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition';
      classes += ` bg-${this.colour}-800 hover:bg-${this.colour}-700 active:bg-${this.colour}-900 focus:border-${this.colour}-900 focus:ring-${this.colour}-300`;
      return `${classes} ${this.class}`;
    },
  },

};
</script>
