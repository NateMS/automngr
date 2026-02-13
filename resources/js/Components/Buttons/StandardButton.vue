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
      const colourClasses = {
        gray: 'bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 focus:ring-gray-300',
        green: 'bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:ring-green-300',
      };
      let classes = 'justify-center inline-flex items-center sm:px-4 sm:py-2 px-2 py-1 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition';
      classes += ` ${colourClasses[this.colour] || colourClasses.gray}`;
      return `${classes} ${this.class}`;
    },
  },

};
</script>
