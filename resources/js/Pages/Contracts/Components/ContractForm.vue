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
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="date" value="Datum" />
                    <jet-input id="date" type="text" class="mt-1 block w-full" v-model="form.date" ref="date" autocomplete="date" />
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