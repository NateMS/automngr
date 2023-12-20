<template>
    <div class="col-span-6 sm:col-span-4">
            <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="firstname" value="Vorname" />
                <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" ref="firstname" autocomplete="firstname" />
                <jet-input-error :message="form.errors.firstname" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="lastname" value="Nachname" />
                <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" ref="lastname" autocomplete="lastname" />
                <jet-input-error :message="form.errors.lastname" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="company" value="Firma" />
        <jet-input id="company" type="text" class="mt-1 block w-full" v-model="form.company" ref="company" autocomplete="company" />
        <jet-input-error :message="form.errors.company" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <jet-label for="address" value="Strasse" />
        <jet-input id="address" type="text" class="mt-1 block w-full" v-model="form.address" ref="address" autocomplete="address" />
        <jet-input-error :message="form.errors.address" class="mt-2" />
    </div>

    <div class="col-span-6 sm:col-span-4">
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-2">
                <jet-label for="zip" value="PLZ" />
                <jet-input id="zip" type="text" class="mt-1 block w-full" v-model="form.zip" ref="zip" autocomplete="zip" />
                <jet-input-error :message="form.errors.zip" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="city" value="Ort" />
                <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" ref="city" autocomplete="city" />
                <jet-input-error :message="form.errors.city" class="mt-2" />
            </div>
                <div class="col-span-6 sm:col-span-1">
                <jet-label for="country" value="Land" />
                <jet-input id="country" type="text" class="mt-1 block w-full" v-model="form.country" ref="country" autocomplete="country" />
                <jet-input-error :message="form.errors.country" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="col-span-6 sm:col-span-4">
            <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="email" value="E-Mail" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" ref="email" autocomplete="email" />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="phone" value="Telefon" />
                <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" ref="phone" autocomplete="phone" />
                <jet-input-error :message="form.errors.phone" class="mt-2" />
            </div>
        </div>
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
import JetInputError from '@/Jetstream/InputError';

export default {
  components: {
    JetLabel,
    JetInput,
    JetInputError,
  },
  props: {
    form: Object,
  },
  watch: {
    'form.zip': function(newVal, oldVal) {
      this.fetchCity(newVal, oldVal);
    },
  },
  methods: {
    fetchCity(newVal, oldVal) {
      if (newVal !== oldVal && newVal.length === 4 && this.form.country === 'CH') {
        axios.get(`https://openplzapi.org/ch/Localities?postalCode=${newVal}`)
          .then((response) => {
            console.log(response);
            let data = response.data;
            console.log(data);
            /*if (records.length > 1) {
              records = records.filter(rec => rec.geometry);
            }*/
            if (data[0]) {
              this.form.city = data[0].name;
            }
          });
      }
    },
  },
};
</script>
