<template>
    <standard-button colour="green" :href="link">
        <unicon fill="currentColor" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
        {{ text }}
    </standard-button>
</template>

<script>
import StandardButton from './StandardButton.vue';

export default {
    components: {
      StandardButton,
    },
    props: {
        type: Number,
        contactId: Number,
        carId: Number
    },
    computed: {
        text() {
            return this.type === 1 ? 'Verkaufsvertrag' : 'Ankaufsvertrag';
        },
        link() {
            // return route('contracts.create') + '?carId=1&contactId=1&type=' + String(this.type);
            if (this.contactId && this.carId) {
                return route('contracts.create', {type: this.type, car: this.carId, contact: this.contactId});
            }
            if (this.contactId) {
                return route('contracts.create', {type: this.type, contact: this.contactId});
            }
            return route('contracts.create',  {type: this.type, car: this.carId, contact: this.contactId});
        },
    },
};
</script>
