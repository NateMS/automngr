<template>
    <div class="col-span-6 sm:col-span-4">
        <jet-label for="brand" value="Marke" />
        <multiselect v-model="brandSelection" @SearchChange="updateBrandSearch" @select="updateBrand" label="name" track-by="id" :options="brands" class="mt-1 block w-full border-gray-300" placeholder="Marke auswählen">
            <template v-slot:noResult>
                <span @click="addBrand">
                    <b>{{ brandSearch }}</b> als neue Marke speichern?
                </span>
            </template>
        </multiselect>
    </div>

    <div v-if="brandSelection" class="col-span-6 sm:col-span-4">
        <jet-label for="model" value="Modell" />
        <multiselect v-model="car_modelSelection" @SearchChange="updateCarModelSearch" @select="updateCarModel" label="name" track-by="id" :options="carModels" class="mt-1 block w-full border-gray-300" placeholder="Modell auswählen">
            <template v-slot:noResult>
                <span @click="addCarModel">
                    <b>{{ modelSearch }}</b> als neues {{ brand.name }}-Modell speichern?
                </span>
            </template>
        </multiselect>
        <jet-input-error :message="form.errors.car_model_id" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-5">
                <jet-label for="stammnummer" value="Stammnummer" />
                <jet-input id="stammnummer" type="text" class="mt-1 block w-full" v-model="form.stammnummer" ref="stammnummer" autocomplete="stammnummer" />
                <jet-input-error :message="form.errors.stammnummer" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-7">
                <jet-label for="vin" value="Chassisnummer" />
                <jet-input id="vin" type="text" class="mt-1 block w-full" v-model="form.vin" ref="vin" autocomplete="vin" />
                <jet-input-error :message="form.errors.vin" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="col-span-6 sm:col-span-4">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-6 sm:col-span-6">
                <jet-label for="initial_date" value="Inverkehrssetzung" />
                <datepicker id="initial_date" ref="initial_date" v-model="form.initial_date" inputFormat="dd.MM.yyyy" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                <jet-input-error :message="form.errors.initial_date" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <jet-label for="last_check_date" value="Letzte Prüfung" />
                <datepicker id="last_check_date" ref="last_check_date" v-model="form.last_check_date" inputFormat="dd.MM.yyyy" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                <jet-input-error :message="form.errors.last_check_date" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="kilometers" value="Kilometerstand" />
        <currency-input v-model="form.kilometers" :options="currencyOptions" id="kilometers" class="w-full mt-1 block border-gray-300 rounded-md shadow-sm" ref="kilometers" />
        <jet-input-error :message="form.errors.kilometers" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="colour" value="Farbe" />
        <jet-input id="colour" type="text" class="mt-1 block w-full" v-model="form.colour" ref="colour" autocomplete="colour" />
        <jet-input-error :message="form.errors.colour" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="known_damage" value="Bekannter Schaden" />
        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.known_damage" ref="input">
        </textarea>
        <jet-input-error :message="form.errors.known_damage" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="notes" value="Bemerkungen" />
        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.notes" ref="input">
        </textarea>
        <jet-input-error :message="form.errors.notes" class="mt-2" />
    </div>
</template>

<script>
import JetLabel from '@/Jetstream/Label.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetActionMessage from '@/Jetstream/ActionMessage';
import JetInputError from '@/Jetstream/InputError';
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue3-datepicker';
import CurrencyInput from '@/Components/CurrencyInput';

export default {
  components: {
    JetLabel,
    JetInput,
    JetInputError,
    JetActionMessage,
    Multiselect,
    Datepicker,
    CurrencyInput,
  },
  props: {
    form: Object,
    brands: Array,
    brand: Object,
    car_model: Object,
  },
  data() {
    return {
      brandSearch: null,
      modelSearch: null,
      carModels: [],
      brandSelection: this.brand,
      car_modelSelection: this.car_model,
      currencyOptions: {
        currency: 'CHF',
        locale: 'de-CH',
        exportValueAsInteger: true,
        hideGroupingSeparatorOnFocus: false,
        precision: 0,
        currencyDisplay: 'hidden',
      },
    };
  },
  methods: {
    updateBrand(brand) {
      if (brand) {
        this.brand.id = brand.id;
        this.brand.name = brand.name;
        this.brand.models = brand.models;
      } else {
        this.brand.id = null;
        this.brand.name = null;
        this.brand.models = [];
      }
      this.updateCarModelsList(brand);
    },
    updateCarModel(car_model) {
      if (car_model) {
        this.car_model.id = car_model.id;
        this.car_model.name = car_model.name;
        this.form.car_model_id = car_model.id;
      } else {
        this.car_model.id = null;
        this.car_model.name = null;
        this.form.car_model_id = null;
      }
    },
    updateCarModelsList(brand) {
      this.carModels = brand.models ?? [];
      this.car_modelSelection = null;
      this.updateCarModel(null);
    },
    updateBrandSearch(searchQuery, id) {
      this.brandSearch = searchQuery;
    },
    addBrand() {
      axios.post(this.route('brands.store'), {
        name: this.brandSearch,
      }).then((response) => {
        this.brandSelection = response.data;
        this.brands.push(this.brandSelection);
        this.updateBrand(this.brandSelection);
      });
    },
    updateCarModelSearch(searchQuery, id) {
      this.modelSearch = searchQuery;
    },
    addCarModel() {
      axios.post(this.route('models.store'), {
        name: this.modelSearch,
        brand_id: this.brand.id,
      }).then((response) => {
        this.car_modelSelection = response.data;
        this.carModels.push(this.car_modelSelection);
        this.updateCarModel(this.car_modelSelection);
      });
    },
  },
  mounted() {
    this.$nextTick(function () {
      this.brandSelection = this.brands.find((x) => x.id === this.brand.id);
      if (this.brandSelection) {
        this.carModels = this.brandSelection.models ?? [];
      }
    });
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
