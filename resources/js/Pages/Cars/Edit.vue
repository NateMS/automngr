<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                <bread-crumb :text="name" :href="route('cars.show', car.id)" />
                bearbeiten
            </h2>
        </template>
        <div>
            <car-form :data="data" :meta="meta" :car_model="car_model" :brand="brand" :brands="brands">
                <template #title>Autoangaben</template>
                <template #description>
                    Autodetails &amp; anpassen.
                </template>
            </car-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import CarForm from './Components/CarForm.vue';

export default {
  components: {
    BreadCrumb,
    Layout,
    CarForm,
  },
  props: {
    car: Object,
    brands: Array,
  },
  computed: {
    name() {
      let out = '';
      if (this.brand.name) {
        out += this.brand.name;
        if (this.car_model.name) {
          out += ` ${this.car_model.name}`;
        }
      }
      return out;
    },
  },
  data() {
    return {
      currentRoute: 'cars.edit',
      meta: {
        form_name: `EditCar${this.car.id}`,
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
        initial_date: new Date(this.car.initial_date).toJSON().slice(0,10).split('-').reverse().join('.'),
        colour: this.car.colour,
        notes: this.car.notes,
        car_model_id: this.car.car_model.id,
        last_check_date: new Date(this.car.last_check_date).toJSON().slice(0,10).split('-').reverse().join('.'),
        kilometers: this.car.kilometers,
        known_damage: this.car.known_damage,
        notes: this.car.notes,
      },
    };
  },
};
</script>
