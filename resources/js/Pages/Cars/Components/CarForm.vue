<template>
    <div class="max-w-7xl py-10 sm:px-6 lg:px-8">
        <jet-form-section @submitted="submitForm">
            <template #title>
                <slot name="title"></slot>
            </template>

            <template #description>
                <slot name="description"></slot>
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="brand" value="Marke" />
                    <multiselect v-model="brand" @SearchChange="updateBrandSearch" @select="updateCarModelsList" label="name" track-by="id" :options="brands" class="mt-1 block w-full" placeholder="Marke auswählen">
                        <template v-slot:noResult>
                            <span @click="addBrand">
                                <b>{{ brandSearch }}</b> als neue Marke speichern?
                            </span>
                        </template>
                    </multiselect>
                </div>

                <div v-if="brand" class="col-span-6 sm:col-span-4">
                    <jet-label for="model" value="Modell" />
                    <multiselect label="name" track-by="id" v-model="model" @SearchChange="updateCarModelSearch" :options="carModels" class="mt-1 block w-full" placeholder="Modell auswählen">
                        <template v-slot:noResult>
                            <span @click="addCarModel">
                                <b>{{ modelSearch }}</b> als neues {{ brand.name }}-Modell speichern?
                            </span>
                        </template>
                    </multiselect>
                    <jet-input-error :message="form.errors.model" class="mt-2" />
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
                            <jet-input id="initial_date" type="text" class="mt-1 block w-full" v-model="form.initial_date" ref="initial_date" autocomplete="initial_date" />
                            <jet-input-error :message="form.errors.initial_date" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <jet-label for="last_check_date" value="Letzte Prüfung" />
                            <jet-input id="last_check_date" type="text" class="mt-1 block w-full" v-model="form.last_check_date" ref="last_check_date" autocomplete="last_check_date" />
                            <jet-input-error :message="form.errors.last_check_date" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="kilometers" value="Kilometerstand" />
                    <jet-input id="kilometers" type="text" class="mt-1 block w-full" v-model="form.kilometers" ref="kilometers" autocomplete="kilometers" />
                    <jet-input-error :message="form.errors.kilometers" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="colour" value="Farbe" />
                    <jet-input id="colour" type="text" class="mt-1 block w-full" v-model="form.colour" ref="colour" autocomplete="colour" />
                    <jet-input-error :message="form.errors.colour" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="known_damage" value="Bekannter Schaden" />
                    <textarea class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="form.known_damage" ref="input">
                    </textarea>
                    <jet-input-error :message="form.errors.known_damage" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="notes" value="Bemerkungen" />
                    <textarea class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="form.notes" ref="input">
                    </textarea>
                    <jet-input-error :message="form.errors.notes" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    {{ meta.on_success }}
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ meta.button_text }}
                </jet-button>
            </template>
        </jet-form-section>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import Modal from '@/Jetstream/Modal.vue'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetInputError from '@/Jetstream/InputError'
import JetFormSection from '@/Jetstream/FormSection'
import Multiselect from 'vue-multiselect'

export default {
    components: {
        JetButton,
        JetFormSection,
        JetLabel,
        Modal,
        JetInput,
        JetInputError,
        JetActionMessage,
        Multiselect,
    },
    props: {
        form: Object,
        brands: Array,
        meta: Object,
    },
    data() {
        return {
            brand: this.form.brand,
            brandSearch: null,
            modelSearch: null,
            carModels: [],
            model: this.form.model,
        }
    },
    methods: {
        submitForm() {
            this.form.post(route(this.meta.link, this.form.data()), {
                preserveScroll: true,
            });
        },
        updateCarModelsList(brand) {
            this.carModels = brand.models ?? [];
            this.model = null;
        },
        updateBrandSearch(searchQuery, id) {
            this.brandSearch = searchQuery
        },
        addBrand() {
            axios.post(this.route('brands.store'), {
                name: this.brandSearch,
            }).then((response) => {
                let newBrand = response.data;
                this.brands.push(newBrand);
                this.brand = newBrand;
            });
        },
        updateCarModelSearch(searchQuery, id) {
            this.modelSearch = searchQuery
        },
        addCarModel() {
            axios.post(this.route('models.store'), {
                name: this.modelSearch,
                brand_id: this.brand.id,
            }).then((response) => {
                let newModel = response.data;
                this.carModels.push(newModel);
                this.model = newModel;
            });
        },
    },
    computed: {
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>