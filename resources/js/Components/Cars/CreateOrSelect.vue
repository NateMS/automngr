<template>
  <jet-form-section :emptyBg="existing_car.id !== null">
      <template #title>
          Auto
      </template>
      <template #description>
          {{ existing_car.id ? 'Ausgewähltes Auto' : 'Auto auswählen oder neu erfassen' }} 
      </template>
      <template v-if="existing_car.id" #form>
          <car-card v-if="car.id" class="xl:col-span-3 md:col-span-4 col-span-6" :car="car" hideEmpty="true" />
      </template>
      <template v-else #form>
          <div class="col-span-6">
              <jet-label for="car" value="Auto auswählen" />
              <div class="grid grid-cols-12 gap-3 gap-y-6 mt-1">
                <multiselect :allow-empty="false" @select="onCarChange" :disabled="createCar" v-model="car" label="name" track-by="id" :options="carsChoice" class="2xl:col-span-4 sm:col-span-6 col-span-12" placeholder="Auto auswählen" />
                <div v-if="!createCar" class="sm:col-span-6 col-span-12">
                  <span class="mr-2">oder</span>
                  <button @click="openCarForm" class="bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                      Neu erfassen
                  </button>
                </div>
            </div>
          </div>
          <div v-if="car.id" class="col-span-6">
              <car-card class="mt-3 xl:col-span-3 md:col-span-4 col-span-6" :car="car" hideEmpty="true" />
          </div>
          <div v-if="createCar" class="col-span-6">
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
  </jet-form-section>
</template>

<script>
import CarFormFields from '@/Pages/Cars/Components/CarFormFields.vue';
import CarCard from '@/Components/CarCard.vue';
import JetFormSection from '@/Jetstream/FormSection';
import Multiselect from 'vue-multiselect';
import JetLabel from '@/Jetstream/Label.vue';
import JetButton from '@/Jetstream/Button';

export default {
  components: {
    CarCard,
    CarFormFields,
    JetFormSection,
    Multiselect,
    JetLabel,
    JetButton,

  },
  props: {
    existing_car: Object,
    cars: Object,
    brands: Object,
    type: String,
  },
  data() {
    return {
      carsChoice: this.cars,
      car: {
        id: this.existing_car?.id ?? null,
        name: this.existing_car?.name ?? null,
        stammnummer: this.existing_car?.stammnummer ?? null,
        vin: this.existing_car?.vin ?? null,
        colour: this.existing_car?.colour ?? null,
        car_model_id: this.existing_car?.car_model_id ?? null,
        initial_date: this.existing_car?.initial_date ?? null,
        last_check_date: this.existing_car?.last_check_date ?? null,
        kilometers: this.existing_car?.kilometers ?? null,
        known_damage: this.existing_car?.known_damage ?? null,
        notes: this.existing_car?.notes ?? null,
        errors: {},
      },
      brand: { id: null, name: null },
      car_model: { id: null, name: null },
      createCar: false,
    };
  },
  computed: {
    contractType() {
      return this.isSellContract ? 'Verkaufsvertrag' : 'Ankaufsvertrag';
    },
    contactType() {
      return this.isSellContract ? 'Käufer' : 'Verkäufer';
    },
    isSellContract() {
      return this.type == '1';
    },
    emptyCar() {
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
    openCarForm() {
      this.createCar = true;
      this.car = this.emptyCar;
    },
    onCarChange(car, val) {
        this.triggerChange(car.id);
    },
    triggerChange(val) {
        this.$emit('car-id-change', val);
    },
    submitCreateCarForm(e) {
      e.preventDefault();
      axios.post(this.route('cars.store_for_contract'), this.car)
        .then((res) => {
          this.carsChoice.push(res.data);
          this.car = res.data;
          this.createCar = false;
          this.triggerChange(this.car.id);
        }).catch((err) => {
          if (err.response) {
            const { errors } = err.response.data;

            Object.keys(errors).map((key, index) => {
              errors[key] = errors[key].join(' ');
            });
            this.car.errors = errors;
          }
        });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
