<template>
    <a target="_blank" :href="document.link" class="p-3 col-span-2 grid relative grid-flow-rows cursor-pointer group auto-rows-max hover:bg-white bg-gray-50 transition shadow rounded-md font-medium">
        <inertia-link :href="route('documents.destroy', document.id)" class="absolute right-0 opacity-0 group-hover:opacity-80 transition">
            <unicon fill="red" class="p-2" height="40" width="40" name="trash-alt"></unicon>
        </inertia-link>
        <span class="justify-center inline-flex items-center mx-auto">
            <unicon fill="royalblue" class="p-2" height="70" width="70" name="file-alt"></unicon>
            <span class="text-blue-800 text-xl font-bold">{{ document.extension }}</span>
        </span>
        <span class="overflow-ellipsis overflow-hidden"> {{ document.name }}</span>
        <span class="text-xs text-grey">{{ document.created_at }}, {{ document.size }}</span>
    </a>
</template>

<script>

export default {
    components: {
    },
    props: {
        document: Object,
    },
    data() {
        return {
            
        }
    },
}
</script>