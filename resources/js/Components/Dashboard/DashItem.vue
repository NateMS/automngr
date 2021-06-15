<template>
    <div class="col-span-4 xs:col-span-6 shadow bg-white rounded-md sm:rounded-lg text-center">
        <inertia-link v-if="link" :href="link" class="group">
            <p class="font-bold text-6xl text-indigo-600 group-hover:text-indigo-700 py-5 transition">{{ number }}</p>
            <p class="font-semibold text-2xl bg-indigo-600 group-hover:bg-indigo-700 text-white py-4 rounded-b-lg transition">{{ title }}</p>  
        </inertia-link>
        <span v-else>
            <p class="font-bold text-6xl text-indigo-600 py-5">{{ number }}</p>
            <p class="font-semibold text-2xl bg-indigo-600 text-white py-4 rounded-b-lg">{{ title }}</p>  
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