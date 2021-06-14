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
                            ? 'w-full flex items-center text-blue-600 h-10 pl-4 bg-gray-200 hover:bg-gray-200 rounded-lg cursor-pointer transition'
                            : 'w-full flex items-center text-gray-800 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer transition'
            }
        }
    }
</script>
