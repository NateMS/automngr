<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                {{ title }}
            </h2>
        </template>
   
        <div>
            <div class="max-w-7xl py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="submitForm">
                    <template #title>
                        Kontaktinformationen
                    </template>

                    <template #description>
                        Kontaktinformationen anschauen &amp; anpassen.
                        <contact-card :contact="computedContact" />
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                             <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="firstname" value="Vorname" />
                                    <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" ref="firstname" autocomplete="firstname" />
                                    <jet-input-error :message="form.errors.firstname" class="mt-2" />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="lastname" value="Nachname" />
                                    <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" ref="lastname" autocomplete="lastname" />
                                    <jet-input-error :message="form.errors.lastname" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="company" value="Firma" />
                            <jet-input id="company" type="text" class="mt-1 block w-full" v-model="form.company" ref="company" autocomplete="company" />
                            <jet-input-error :message="form.errors.company" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="address" value="Strasse" />
                            <jet-input id="address" type="text" class="mt-1 block w-full" v-model="form.address" ref="address" autocomplete="address" />
                            <jet-input-error :message="form.errors.address" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-2">
                                    <jet-label for="zip" value="PLZ" />
                                    <jet-input id="zip" type="text" class="mt-1 block w-full" v-model="form.zip" ref="zip" autocomplete="zip" />
                                    <jet-input-error :message="form.errors.zip" class="mt-2" />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="city" value="Ort" />
                                    <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" ref="city" autocomplete="city" />
                                    <jet-input-error :message="form.errors.city" class="mt-2" />
                                </div>
                                 <div class="col-span-6 sm:col-span-1">
                                    <jet-label for="country" value="Land" />
                                    <jet-input id="country" type="text" class="mt-1 block w-full" v-model="form.country" ref="country" autocomplete="country" />
                                    <jet-input-error :message="form.errors.country" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                             <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="email" value="E-Mail" />
                                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" ref="email" autocomplete="email" />
                                    <jet-input-error :message="form.errors.email" class="mt-2" />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="phone" value="Telefon" />
                                    <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" ref="phone" autocomplete="phone" />
                                    <jet-input-error :message="form.errors.phone" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="notes" value="Bemerkungen" />
                            <textarea class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="form.notes" ref="input">
                            </textarea>
                            <jet-input-error :message="form.errors.notes" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            Änderungen gespeichert.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Änderungen speichern
                        </jet-button>
                    </template>
                </jet-form-section>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <simple-table :title="'An ' + title + ' verkaufte Autos'" :data="contact.bought_cars" :columns="boughtCarColumns" />
            </div>
            <div class="max-w-7xl pt-6 sm:px-6 lg:px-8">    
                <simple-table :title="'Von ' + title + ' gekaufte Autos'" :data="contact.sold_cars" :columns="soldCarColumns" />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import JetButton from '@/Jetstream/Button'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContactCard from '@/Components/ContactCard.vue'
import SimpleTable from '@/Components/SimpleTable.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetInputError from '@/Jetstream/InputError'
import JetFormSection from '@/Jetstream/FormSection'

export default {
    components: {
        JetButton,
        JetFormSection,
        Layout,
        BreadCrumb,
        SimpleTable,
        JetLabel,
        JetInput,
        JetInputError,
        JetActionMessage,
        ContactCard,
    },

    props: {
        contact: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                _method: 'PUT',
                firstname: this.contact.firstname,
                lastname: this.contact.lastname,
                company: this.contact.company,
                email: this.contact.email,
                phone: this.contact.phone,
                address: this.contact.address,
                zip: this.contact.zip,
                city: this.contact.city,
                country: this.contact.country,
                notes: this.contact.notes,
            }),
            boughtCarColumns: [
                {key: 'name', value: 'Auto'},
                {key: 'date', value: 'Verkaufsdatum'},
                {key: 'price', value: 'Verkaufspreis'},
                {key: 'insurance_type', value: 'Versicherungstyp'},
            ],
            soldCarColumns: [
                {key: 'name', value: 'Auto'},
                {key: 'date', value: 'Kaufdatum'},
                {key: 'price', value: 'Kaufpreis'},
            ]
        }
    },
    computed: {
        title: function () {
            if (this.form.company) {
                return this.form.company;
            }

            return this.form.lastname + ' ' + this.form.firstname;
        }, 
        computedContact: function () {
            return {
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                company: this.form.company,
                email: this.form.email,
                phone: this.form.phone,
                address: this.form.address,
                zip: this.form.zip,
                city: this.form.city,
                country: this.form.country,
            }
        }
    },

    methods: {
        submitForm() {
            this.form.post(route('contacts.update', this.contact), {
                preserveScroll: true,
            });
        },
    },
}
</script>