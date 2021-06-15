<template>
    <a :href="href" target="_blank" :class="allClasses">
        <unicon fill="white" class="mr-1" height="22" width="22" name="file-download"></unicon>
        drucken
    </a>
</template>

<script>
export default {
    props: {
        class: String,
        href: String,
    },
    data() {
        return {
            colour: 'indigo',
        }
    },
    computed: {
        allClasses: function () {
            let classes = "justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition"
            classes += ` bg-${this.colour}-800 hover:bg-${this.colour}-700 active:bg-${this.colour}-900 focus:border-${this.colour}-900 focus:ring-${this.colour}-300`;
            return classes + " " + this.class;
        }
    },
}
</script>