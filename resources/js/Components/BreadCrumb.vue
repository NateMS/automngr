<template>
    <inertia-link :href="href" class="text-indigo-400 hover:text-indigo-600" >
        {{ text }} /
    </inertia-link>
</template>

<script>
export default {
  props: {
    href: String,
    text: String,
  },
}
</script>
