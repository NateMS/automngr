<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Einstellungen
            </h2>
        </template>

        <div class="max-w-7xl">
            <jet-form-section @submitted="submitForm">
                <template #title>Firmendaten</template>
                <template #description>Diese Informationen werden auf Verträgen und Quittungen angezeigt.</template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="company_name" value="Firmenname" />
                        <jet-input id="company_name" type="text" class="mt-1 block w-full" v-model="form.company_name" />
                        <jet-input-error :message="form.errors.company_name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="street" value="Strasse" />
                        <jet-input id="street" type="text" class="mt-1 block w-full" v-model="form.street" />
                        <jet-input-error :message="form.errors.street" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="city" value="PLZ / Ort" />
                        <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                        <jet-input-error :message="form.errors.city" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <jet-label for="phone" value="Telefon" />
                                <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                                <jet-input-error :message="form.errors.phone" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <jet-label for="email" value="E-Mail" />
                                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                <jet-input-error :message="form.errors.email" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <jet-label for="vat_number" value="MwSt-Nr" />
                                <jet-input id="vat_number" type="text" class="mt-1 block w-full" v-model="form.vat_number" />
                                <jet-input-error :message="form.errors.vat_number" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <jet-label for="vat_rate" value="MwSt-Satz (%)" />
                                <jet-input id="vat_rate" type="number" step="0.1" class="mt-1 block w-full" v-model="form.vat_rate" />
                                <jet-input-error :message="form.errors.vat_rate" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="iban" value="IBAN" />
                        <jet-input id="iban" type="text" class="mt-1 block w-full" v-model="form.iban" />
                        <jet-input-error :message="form.errors.iban" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="signature_city" value="Ort für Unterschrift" />
                        <jet-input id="signature_city" type="text" class="mt-1 block w-full" v-model="form.signature_city" />
                        <jet-input-error :message="form.errors.signature_city" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="logo" value="Logo" />
                        <div class="mt-2" v-if="logoPreview || settings.logo_url">
                            <img :src="logoPreview || settings.logo_url" class="max-h-20 mb-3" />
                        </div>
                        <input type="file" id="logo" ref="logo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            @change="selectLogo" />
                        <jet-input-error :message="form.errors.logo" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                        Einstellungen gespeichert.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Speichern
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: {
        Layout,
        JetButton,
        JetFormSection,
        JetActionMessage,
        JetLabel,
        JetInput,
        JetInputError,
    },
    props: {
        settings: Object,
    },
    data() {
        return {
            form: useForm({
                company_name: this.settings.company_name,
                street: this.settings.street,
                city: this.settings.city,
                phone: this.settings.phone,
                email: this.settings.email,
                vat_number: this.settings.vat_number,
                iban: this.settings.iban,
                signature_city: this.settings.signature_city,
                vat_rate: this.settings.vat_rate,
                logo: null,
            }),
            logoPreview: null,
        };
    },
    methods: {
        selectLogo(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.logo = file;
                this.logoPreview = URL.createObjectURL(file);
            }
        },
        submitForm() {
            this.form.post(route('settings.update'), {
                preserveScroll: true,
            });
        },
    },
};
</script>
