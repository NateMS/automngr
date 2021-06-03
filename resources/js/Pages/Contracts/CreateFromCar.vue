<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Neuen Vertrag erstellen
            </h2>
        </template>
        <div>
            <jet-form-section class="max-w-7xl">
                <template #title>
                    Auto
                </template>
                <template #description>
                    Ausgewähltes Auto für diesen Vertrag
                </template>
                <template #form>
                    <car-card class="col-span-12" :car="car" />
                </template>
            </jet-form-section>
            <jet-form-section class="max-w-7xl py-5 sm:px-6 lg:px-8">
                <template #title>
                    Kontakt
                </template>
                <template #description>
                    Kontakt auswählen oder neu erfassen
                    <contact-card v-if="contact.firstname || contact.company" class="col-span-4" :contact="contact" />
                </template>
                <template #form>
                    <div class="col-span-3">
                        <jet-label for="contact" value="Marke" />
                        <jet-input id="contact" type="text" class="mt-1 block w-full" v-model="contact.id" ref="contact" autocomplete="contact" />
                        <!-- <jet-input-error :message="form.errors.contact" class="mt-2" /> -->
                    </div>
                    <div class="col-span-6">
                        oder
                    </div>
                    <div class="col-span-6">
                        <standard-button class="" href="" colour="gray">Neu erfassen</standard-button>
                    </div>
                </template>
            </jet-form-section>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContactForm from '@/Pages/Contacts/Components/ContactForm.vue'
import CarCard from '@/Components/CarCard.vue'
import ContactCard from '@/Components/ContactCard.vue'
import JetFormSection from '@/Jetstream/FormSection'
import StandardButton from '@/Components/Buttons/StandardButton.vue'

export default {
    components: {
        Layout,
        BreadCrumb,
        CarCard,
        ContactCard,
        JetFormSection,
        ContactForm,
        StandardButton,
    },
    props: {
        car: Object,
        contacts: Object,
        type: String,
    },
    data() {
        return {
            meta: {
                link: 'contacts.store',
                button_text: 'Kontakt speichern',
                on_success: 'Kontakt gespeichert',
            },
            contact: {
                id: null,
                firstname: null,
                lastname: null,
                company: null,
                email: null,
                phone: null,
                address: null,
                zip: null,
                city: null,
                country: null,
                notes: null,
            },
            meta: {
                form_name: 'CreateContractFromCar',
                // route: this.route('contracts.create', this.car.id, this.contact.id),
                method: 'post',
                button_text: 'Vertrag speichern',
                on_success: 'Vertrag gespeichert',
            },
        }
    },
}
</script>