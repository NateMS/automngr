<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Neues Auto erfassen
            </h2>
        </template>
   
        <div>
            <car-form :form="form" :meta="meta">
                <template #title>Neues Auto erfassen</template>
                <template #description>...</template>
            </car-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import CarForm from './Components/CarForm.vue'

export default {
    components: {
        Layout,
        BreadCrumb,
        CarForm,
    },
    data() {
        return {
            meta: {
                link: 'cars.store',
                button_text: 'Auto speichern',
                on_success: 'Auto gespeichert',
            },
            form: this.$inertia.form({
                _method: 'POST',
                id: null,
                stammnummer: null,
                vin: null,
                colour: null,
                model_id: null,
                initial_date: null,
                last_check_date: null,
                kilometers: null,
                known_damage: null,
                notes: null,
            }),
        }
    },
}
</script>