<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                Neues Auto erfassen
            </h2>
        </template>

        <div>
            <car-form :data="data" :meta="meta" :car_model="car_model" :brand="brand" :brands="brands">
                <template #title>Neues Auto erfassen</template>
                <template #description>Daten für ein neues Auto eingeben und speichern</template>
            </car-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import { ref } from 'vue';
import CarForm from './Components/CarForm.vue';

export default {
  components: {
    Layout,
    BreadCrumb,
    CarForm,
  },
  props: {
    brands: Array,
  },
  data() {
    return {
      meta: {
        form_name: 'CreateCar',
        route: this.route('cars.store'),
        method: 'post',
        button_text: 'Auto speichern',
        on_success: 'Auto gespeichert',
      },
      data: {
        id: null,
        stammnummer: null,
        vin: null,
        colour: null,
        car_model_id: null,
        initial_date: ref(new Date()),
        last_check_date: ref(new Date()),
        kilometers: null,
        known_damage: null,
        notes: null,
      },
      brand: { id: null, name: null },
      car_model: { id: null, name: null },
    };
  },
};
</script>
