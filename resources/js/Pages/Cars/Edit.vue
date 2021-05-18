<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                {{ car.name }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl py-10 sm:px-6 lg:px-8">
                <contact-form :form="form" :meta="meta">
                    <template #title>Autoangaben</template>
                    <template #description>
                        Autodetails anschauen &amp; anpassen.
                    </template>
                </contact-form>
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
    },
    computed: {
        title: function () {
            // if (this.form.company) {
            //     return this.form.company;
            // }

            // return this.form.lastname + ' ' + this.form.firstname;
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
            currentRoute: 'car.edit',
            meta: {
                link: 'cars.update',
                button_text: 'Änderungen speichern',
                on_success: 'Änderungen gespeichert',
            },
            form: this.$inertia.form({
                _method: 'PUT',
                id: this.car.id,
                stammnummer: this.car.stammnummer,
                vin: this.car.vin,
                initial_date: this.car.initial_date,
                colour: this.car.colour,
                notes: this.car.notes,
                model_id: this.car.model_id,
                last_check_date: this.car.last_check_date,
                kilometers: this.car.kilometers,
                known_damage: this.car.known_damage,
                notes: this.car.notes,
            }),
        }
    },
}
</script>