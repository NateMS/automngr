<template>
    <jet-form-section @submitted="submitForm">
        <template #title>
            <slot name="title"></slot>
        </template>

        <template #description>
            <slot name="description"></slot>
        </template>

        <template #form>
            <div class="col-span-3 grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="date" value="Datum" />
                    <datepicker id="date" ref="date" v-model="form.date" inputFormat="dd.MM.yyyy" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                    <jet-input-error :message="form.errors.date" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="delivery_date" value="Lieferdatum" />
                    <datepicker id="delivery_date" ref="delivery_date" v-model="form.delivery_date" inputFormat="dd.MM.yyyy" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                    <jet-input-error :message="form.errors.delivery_date" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="price" :value="form.type === '0' ? 'Einkaufspreis' : 'Verkaufspreis'" />
                    <currency-input v-model="form.price" :options="currencyOptions" id="price" class="w-full mt-1 block border-gray-300 rounded-md shadow-sm" ref="price"/>
                    <jet-input-error :message="form.errors.price" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="amount" value="Anzahlung" />
                    <currency-input v-model="form.amount" :options="currencyOptions" id="price" class="w-full mt-1 block border-gray-300 rounded-md shadow-sm" ref="amount"/>
                    <jet-input-error :message="form.errors.amount" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="payment_type" value="Einzahlungsart" />
                    <select v-model="form.payment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="0" :selected="form.payment_type == '0'">Banküberweisung</option>
                        <option value="1" :selected="form.payment_type == '1'">Barzahlung</option>
                    </select>
                    <jet-input-error :message="form.errors.type" class="mt-2" />
                </div>

                <div v-if="form.is_sell_contract" class="col-span-6 sm:col-span-4">
                    <jet-label for="insurance_type" value="Versicherung" />
                    <select v-model="form.insurance_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option v-for="(insurance, index) in insurance_types" :value="index"  v-bind:key="index" :selected="form.insurance_type == index">{{ insurance }}</option>
                    </select>
                    <jet-input-error :message="form.errors.insurance_type" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="notes" value="Bemerkungen" />
                    <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.notes" ref="input">
                    </textarea>
                    <jet-input-error :message="form.errors.notes" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                {{ meta.on_success }}
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !data.contact_id || !data.car_id">
                {{ meta.button_text }}
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import JetButton from '@/Jetstream/Button';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage';
import JetInputError from '@/Jetstream/InputError';
import JetFormSection from '@/Jetstream/FormSection';
import Datepicker from 'vue3-datepicker';
import { useForm } from '@inertiajs/inertia-vue3';
import CurrencyInput from '@/Components/CurrencyInput';

export default {
  components: {
    JetButton,
    JetFormSection,
    JetLabel,
    JetInputError,
    JetActionMessage,
    Datepicker,
    CurrencyInput,
  },
  props: {
    data: Object,
    meta: Object,
    insurance_types: Array,
  },
  data() {
    return {
      form: useForm(this.meta.form_name, this.data),
      currencyOptions: {
        currency: 'CHF',
        locale: 'de-CH',
        exportValueAsInteger: true,
        hideGroupingSeparatorOnFocus: false,
      },
    };
  },
  methods: {
    submitForm() {
      this.form.car_id = this.data.car_id;
      this.form.contact_id = this.data.contact_id;
      this.form.submit(this.meta.method, this.meta.route);
    },
  },
};
</script>
