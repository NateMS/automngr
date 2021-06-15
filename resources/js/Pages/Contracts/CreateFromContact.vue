<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                <bread-crumb :text="contact.name" :href="route('contacts.show', contact.id)" />
                Neuen {{ contractType }} erstellen
            </h2>
        </template>
        <div>
            <jet-form-section class="max-w-7xl" :emptyBg="true">
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
            <jet-form-section class="max-w-7xl mt-5">
                <template #title>
                    Auto
                </template>
                <template #description>
                    Auto auswählen oder neu erfassen
                </template>
                <template #form>
                    <div class="col-span-3">
                        <jet-label for="car" value="Auto" />
                        <multiselect :disabled="createCar" v-model="car" label="name" track-by="id" :options="carsChoice" class="mt-1 block w-full" placeholder="Auto auswählen" />
                    </div>
                    <div class="col-span-6">
                        <car-card v-if="car.id" class="mt-3 col-span-6" :car="car" />
                    </div>
                    <div class="col-span-6">
                        oder
                    </div>
                    <div v-if="!createCar" class="col-span-6">
                        <button @click="openCarForm" class="bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            Neu erfassen
                        </button>
                    </div>
                    <div v-else class="col-span-6">
                        <p class="w-full mb-1 font-bold">Neues Auto erfassen:</p>
                        <form @submit="submitCreateCarForm">
                            <div class="grid grid-cols-6 gap-6">
                                <car-form-fields :form="car" :car_model="car_model" :brand="brand" :brands="brands" />
                                <div class="col-span-6 sm:col-span-4 flex items-center justify-end text-right">
                                    <jet-button>
                                        Auto speichern
                                    </jet-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </template>
                <template #actions>
                    <jet-button :disabled="!car.id" @click="nextPage">
                        Nächster Schritt
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import CarFormFields from '@/Pages/Cars/Components/CarFormFields.vue'
import CarCard from '@/Components/CarCard.vue'
import ContactCard from '@/Components/ContactCard.vue'
import JetFormSection from '@/Jetstream/FormSection'
import Multiselect from 'vue-multiselect'
import JetLabel from '@/Jetstream/Label.vue'
import JetButton from '@/Jetstream/Button'
import JetActionMessage from '@/Jetstream/ActionMessage'

export default {
    components: {
        Layout,
        BreadCrumb,
        CarCard,
        ContactCard,
        CarFormFields,
        JetFormSection,
        Multiselect,
        JetLabel,
        JetButton,
        JetActionMessage,
    },
    props: {
        contact: Object,
        cars: Object,
        brands: Object,
        type: String,
    },
    data() {
        return {
            carsChoice: this.cars,
            car: {
                id: null,
                stammnummer: null,
                vin: null,
                colour: null,
                car_model_id: null,
                initial_date: null,
                last_check_date: null,
                kilometers: null,
                known_damage: null,
                notes: null,
                errors: {},
            },
            brand: {id: null, name: null},
            car_model: {id: null, name: null},
            createCar: false,
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
        emptyCar: function() {
            return {
                id: null,
                stammnummer: null,
                vin: null,
                colour: null,
                car_model_id: null,
                initial_date: null,
                last_check_date: null,
                kilometers: null,
                known_damage: null,
                notes: null,
                errors: {},
            };
        },
    },
    methods: {
        nextPage() {
            this.$inertia.get(route('contracts.create', {
                type: this.isSellContract ? 1 : 0,
                car: this.car.id,
                contact: this.contact.id,
            }), { preserveScroll: true });
        },
        openCarForm() {
            this.createCar = true;
            this.car = this.emptyCar;
        },
        submitCreateCarForm(e) {
            e.preventDefault();
            axios.post(this.route('cars.store_for_contract'), this.car)
                .then(res => {
                    this.carsChoice.push(res.data);
                    this.car = res.data;
                    this.createCar = false;
                }).catch(err => {
                    if (err.response) {
                        let errors = err.response.data.errors;

                        Object.keys(errors).map(function(key, index) {
                            errors[key] = errors[key].join(' ');
                        });
                        this.car.errors = errors;
                    }
                   
                });
        },
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>