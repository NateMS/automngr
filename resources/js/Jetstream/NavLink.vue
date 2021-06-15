<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'w-full flex items-center text-indigo-100 h-10 pl-4 rounded-lg cursor-pointer transition'
                            : 'w-full flex items-center hover:text-indigo-100 text-indigo-300 h-10 pl-4 rounded-lg cursor-pointer transition'
            }
        }
    }
</script>
