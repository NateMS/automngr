<template>
    <inertia-link :href="href" class="text-indigo-600 hover:text-indigo-400 transition" >
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
