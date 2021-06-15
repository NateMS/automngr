<template>
    <div class="lg:col-span-4 sm:col-span-6 col-span-12 shadow bg-white rounded-md sm:rounded-lg text-center">
        <inertia-link v-if="link" :href="link" class="group">
            <p class="font-bold text-6xl text-indigo-800 group-hover:text-indigo-700 py-5 transition">{{ number }}</p>
            <p class="font-semibold text-2xl bg-indigo-800 group-hover:bg-indigo-700 text-white py-4 rounded-b-lg transition">{{ title }}</p>  
        </inertia-link>
        <span v-else>
            <p class="font-bold text-6xl text-indigo-800 py-5">{{ number }}</p>
            <p class="font-semibold text-2xl bg-indigo-800 text-white py-4 rounded-b-lg">{{ title }}</p>  
        </span>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        number: Number,
        link: String,
    },
}
</script>