<template>
  <layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Berichte
        </h2>
    </template>
    <div class="max-w-2xl mx-auto">
        <jet-form-section>
            <template #title>
                Neuen Bericht erstellen
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-3">
                    <jet-label for="year" value="Jahr" />
                    <select :year class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option v-for="year in years" :value="year" v-bind:key="year" :selected="this.year == year">{{ year }}</option>
                    </select>
                </div>
            </template>

            <template #actions>
                <a :href="link" class="justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300">
                    Bericht drucken
                </a>
            </template>
        </jet-form-section>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import JetLabel from '@/Jetstream/Label.vue';
import { ref } from 'vue'
import JetFormSection from '@/Jetstream/FormSection.vue';

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
    };
  },
  computed: {
    link() {
      return route('reports.print', { year: this.year });
    },
  },
};
</script>
