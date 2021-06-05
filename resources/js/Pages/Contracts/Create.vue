<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb v-if="car_first" text="Autos" :href="route('cars')" />
                <bread-crumb v-else text="Kontakte" :href="route('contacts')" />
                <bread-crumb v-if="car_first" :text="car.name" :href="route('cars.show', car.id)" />
                <bread-crumb :text="contact.name" :href="route('contacts.show', contact.id)" />
                <bread-crumb v-if="!car_first" :text="car.name" :href="route('cars.show', car.id)" />
                Neuen {{ contractType }} erstellen
            </h2>
        </template>
   
        <div>
            <jet-form-section v-if="!car_first" emptyBg="true" class="max-w-7xl mb-5">
                <template #title>
                    {{ contactType }}
                </template>
                <template #description>
                    Ausgewählter {{ contactType }} für diesen {{ contractType }}
                </template>
                <template #form>
                    <contact-card class="col-span-12" :contact="contact" />
                </template>
            </jet-form-section>
            <jet-form-section class="max-w-7xl" emptyBg="true">
                <template #title>
                    Auto
                </template>
                <template #description>
                    Ausgewähltes Auto für diesen {{ contractType }}
                </template>
                <template #form>
                    <car-card class="col-span-12" :car="car" />
                </template>
            </jet-form-section>
            <jet-form-section v-if="car_first" emptyBg="true" class="max-w-7xl mt-5">
                <template #title>
                    {{ contactType }}
                </template>
                <template #description>
                    Ausgewählter {{ contactType }} für diesen {{ contractType }}
                </template>
                <template #form>
                    <contact-card class="col-span-12" :contact="contact" />
                </template>
            </jet-form-section>
            <contract-form class="mt-5" :data="data" :meta="meta">
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
        car_first: Boolean,
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
                type: this.type == "SellContract" ? 0 : 1,
                insurance_type: '0',
                car_id: this.car.id,
                contact_id: this.contact.id,
            },
        }
    },
    computed: {
        contractType: function () {
           return this.isSellContract ? "Verkaufsvertrag" : "Ankaufsvertrag";
        },
        contactType: function () {
           return this.isSellContract ? "Käufer" : "Verkäufer";
        },
        isSellContract: function () {
            return this.type == "SellContract";
        },
    }
}
</script>