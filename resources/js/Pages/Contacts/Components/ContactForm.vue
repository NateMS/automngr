<template>
    <div class="max-w-7xl">
        <jet-form-section @submitted="submitForm">
            <template #title>
                <slot name="title"></slot>
            </template>

            <template #description>
                <slot name="description"></slot>
            </template>

            <template #form>
                <contact-form-fields :form="form" />
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
import JetButton from '@/Jetstream/Button.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import { useForm } from '@inertiajs/vue3';
import ContactFormFields from './ContactFormFields';

export default {
  components: {
    JetButton,
    JetFormSection,
    JetActionMessage,
    ContactFormFields,
  },
  props: {
    data: Object,
    meta: Object,
  },
  data() {
    return {
      form: useForm(this.meta.form_name, this.data),
    };
  },
  methods: {
    submitForm() {
      this.form.submit(this.meta.method, this.meta.route);
    },
  },
};
</script>
