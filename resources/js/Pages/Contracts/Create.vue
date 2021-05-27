<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Neuen Vertrag erstellen
            </h2>
        </template>
   
        <div>
            <jet-form-section class="max-w-7xl pb-5 sm:px-6 lg:px-8">
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
                    Ausgewählter Kontakt für diesen Vertrag
                </template>
                <template #form>
                    <contact-card class="col-span-4" :contact="contact" />
                </template>
            </jet-form-section>
            <contract-form :data="data" :meta="meta">
                <template #title>Vertragsinformationen erfassen</template>
                <template #description>Der Vertrag kann anschliessend gespeichert werden.</template>
            </contract-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContractForm from './Components/ContractForm.vue'
import CarCard from '@/Components/CarCard.vue'
import ContactCard from '@/Components/ContactCard.vue'
import JetFormSection from '@/Jetstream/FormSection'

export default {
    components: {
        Layout,
        BreadCrumb,
        ContractForm,
        CarCard,
        ContactCard,
        JetFormSection,
    },
    props: {
        car: Object,
        contact: Object,
        type: String,
    },
    data() {
        return {
            meta: {
                form_name: 'CreateContract',
                route: this.route('contracts.store'),
                method: 'post',
                button_text: 'Vertrag speichern',
                on_success: 'Vertrag gespeichert',
            },
            data: {
                id: null,
                date: null,
                price: null,
                type: this.type,
                insurance_type: '0',
                car_id: this.car.id,
                contact_id: this.contact.id,
               
            },
        }
    },
}
</script>