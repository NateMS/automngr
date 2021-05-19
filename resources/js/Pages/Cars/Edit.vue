<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                {{ name }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl py-10 sm:px-6 lg:px-8">
                <car-form :data="data" :meta="meta" :car_model="car_model" :brand="brand" :brands="brands">
                    <template #title>Autoangaben</template>
                    <template #description>
                        Autodetails anschauen &amp; anpassen.
                    </template>
                </car-form>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import SimpleTable from '@/Components/SimpleTable.vue'
import CarForm from './Components/CarForm.vue'


export default {
    components: {
        BreadCrumb,
        Layout,
        SimpleTable,
        CarForm,
    },
    props: {
        car: Object,
        brands: Array,
    },
    computed: {
        name: function () {
            let out = '';
            if (this.brand.name) {
                out += this.brand.name;
                if (this.car_model.name) {
                    out += ' ' + this.car_model.name;
                }
            }
            return out;
        }, 
        computedCar: function () {
            return {
                // firstname: this.form.firstname,
                // lastname: this.form.lastname,
                // company: this.form.company,
                // email: this.form.email,
                // phone: this.form.phone,
                // address: this.form.address,
                // zip: this.form.zip,
                // city: this.form.city,
                // country: this.form.country,
            }
        }
    },
    data() {
        return {
            currentRoute: 'cars.edit',
            meta: {
                form_name: 'EditCar' + this.car.id,
                route: this.route('cars.update', this.car.id),
                method: 'put',
                button_text: 'Änderungen speichern',
                on_success: 'Änderungen gespeichert',
            },
            brand: this.car.brand,
            car_model: this.car.car_model,
            data: {
                id: this.car.id,
                stammnummer: this.car.stammnummer,
                vin: this.car.vin,
                initial_date: this.car.initial_date,
                colour: this.car.colour,
                notes: this.car.notes,
                car_model_id: this.car.car_model.id,
                last_check_date: this.car.last_check_date,
                kilometers: this.car.kilometers,
                known_damage: this.car.known_damage,
                notes: this.car.notes,
            },
        }
    },
}
</script>