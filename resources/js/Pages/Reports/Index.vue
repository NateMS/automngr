<template>
  <layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Berichte
        </h2>
    </template>
    <div class="max-w-4xl mx-auto">
        <jet-form-section>
            <template #title>
                Neuen Bericht erstellen
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="year" value="Jahr" />
                    <select v-model="year" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option v-for="year in years" :value="year" v-bind:key="year" :selected="this.year == year">{{ year }}</option>
                    </select>
                </div>
            </template>

            <template #actions>
                <a :href="link" class="justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 focus:ring-gray-300">
                    Bericht drucken
                </a>
            </template>
        </jet-form-section>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import JetLabel from '@/Jetstream/Label.vue'
import JetFormSection from '@/Jetstream/FormSection'

export default {
    components: {
        Layout,
        JetLabel,
        JetFormSection,
    },
    props: {
        year: Number,
        years: Array,
    },
    data() {
        return {
        }
    },
    computed: {
        link() {
            return route('reports.print', {year: this.year});
        },
    },
}
</script>