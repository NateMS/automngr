<template>
  <div class="flex items-center">
    <input class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 rounded-md shadow-sm block w-full" autocomplete="off" type="text" name="search" placeholder="Suchen..." :value="value" @input="$emit('input', $event.target.value)" />
    <button class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-indigo-500" type="button" @click="$emit('reset')">zurücksetzen</button>
  </div>
</template>

<script>

export default {
  components: {
  },
  props: {
    value: String,
    maxWidth: {
      type: Number,
      default: 300,
    },
  },
};
</script>
