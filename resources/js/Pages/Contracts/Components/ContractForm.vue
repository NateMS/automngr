<template>
    <div class="max-w-7xl">
        <jet-form-section @submitted="submitForm">
            <template #title>
                <slot name="title"></slot>
            </template>

            <template #description>
                <slot name="description"></slot>
            </template>

            <template #form>
                <div class="col-span-3 grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="date" value="Datum" />
                        <datepicker id="date" ref="date" v-model="form.date" inputFormat="dd.MM.yyyy" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" />
                        <jet-input-error :message="form.errors.date" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="price" value="Betrag" />
                        <jet-input id="price" type="text" class="mt-1 block w-full" v-model="form.price" ref="price" autocomplete="price" />
                        <jet-input-error :message="form.errors.price" class="mt-2" />
                    </div>

                    <div v-if="form.is_sell_contract" class="col-span-6 sm:col-span-4">
                        <jet-label for="insurance_type" value="Versicherung" />
                        <multiselect class="mt-1 block w-full" @select="updateInsuranceSelection" v-model="insuranceSelection" deselect-label="Kann nicht entfernt werden" track-by="key" label="label" placeholder="Versicherung auswählen" :options="insurances" :searchable="false" :allow-empty="false" />
                        <jet-input-error :message="form.errors.insurance_type" class="mt-2" />
                    </div>
                </div>
                
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    {{ meta.on_success }}
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ meta.button_text }}
                </jet-button>
            </template>
        </jet-form-section>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetInputError from '@/Jetstream/InputError'
import JetFormSection from '@/Jetstream/FormSection'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vue3-datepicker'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    components: {
        JetButton,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetActionMessage,
        Multiselect,
        Datepicker,
    },
    props: {
        data: Object,
        meta: Object,
        insurance_types: Object,
    },
    data() {
        return {
            form: useForm(this.meta.form_name, this.data),
            insuranceSelection: {key: this.data.insurance_type, label: 'asd'},
        }
    },
    computed: {
        insurances: function() {
            let insurances = [];
            for (const label in this.insurance_types) {
                insurances.push({key: this.insurance_types[label], label: label});
            }
            return insurances;
        },
    },
    methods: {
        submitForm() {
            this.form.date = this.form.date.toISOString().split("T")[0];
            this.form.submit(this.meta.method, this.meta.route);
        },
        updateInsuranceSelection(selection) {
            this.form.insurance_type = (selection.key).toString();
        },
    },
    mounted: function () {
        this.$nextTick(function () {
            this.insuranceSelection = this.insurances.find(x => x.key === parseInt(this.data.insurance_type));
        })
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>