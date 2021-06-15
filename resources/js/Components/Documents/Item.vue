<template>
    <a target="_blank" :href="document.link" class="p-3 col-span-2 grid relative grid-flow-rows cursor-pointer group auto-rows-max hover:bg-gray-50 bg-white transition shadow rounded-md font-medium">
        <a @click="deleteDocument" href="#" class="absolute right-0 opacity-0 group-hover:opacity-80 transition">
            <unicon fill="#f54242" hover-fill="red" class="p-2" height="40" width="40" name="trash-alt"></unicon>
        </a>
        <span class="justify-center inline-flex text-indigo-800 group-hover:text-indigo-700 items-center mx-auto">
            <unicon fill="currentColor" class="p-2" height="70" width="70" name="file-alt"></unicon>
            <span class="text-xl font-bold">{{ document.extension }}</span>
        </span>
        <span class="overflow-ellipsis overflow-hidden"> {{ document.name }}</span>
        <span class="text-xs text-grey">{{ document.created_at }}, {{ document.size }}</span>
    </a>
</template>

<script>

export default {
    props: {
        document: Object,
    },
    methods: {
        deleteDocument(e) {
            e.preventDefault();
            this.$emit('delete', this.document.id);
        },
    },
}
</script>