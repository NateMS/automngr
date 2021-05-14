<template>
    <div class="mt-3 p-3 bg-white shadow rounded-md font-medium">
        <div v-if="contact.company" class="font-bold">
            {{ contact.company }}
        </div>
        <div v-if="contact.lastname && contact.firstname">
            {{ contact.lastname + ' ' + contact.firstname }}
        </div>
        <div v-if="contact.address">
            {{ contact.address }}
        </div>
        <div v-if="contact.zip && contact.city">
        {{ contact.zip + ' ' + contact.city + ' ' + contact.country }}
        </div>
        <div v-if="contact.email" class="mt-3">
            <a :href="'mailto:' + contact.email" class="pt-1 pb-1 flex items-center">
                <unicon class="mr-1" height="22" width="22" name="envelope"></unicon>  
                {{ contact.email }}
            </a>
        </div>
        <div v-if="contact.phone" class="mt-1">
            <a :href="'phone:' + contact.phone" class="pt-1 pb-1 flex items-center">
                <unicon class="mr-1" height="22" width="22" name="phone"></unicon>  
                {{ contact.phone }}
            </a>
        </div>
        <div v-if="contact.link" class="pt-3 mt-3 border-t">
            <inertia-link :href="contact.link" class="pt-1 pb-1 flex items-center">
                <unicon class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                Zum Kontakt
            </inertia-link>
        </div>
    </div>
</template>

<script>

export default ({
    components: {
    },

    props: {
        contact: Object,
    },
    computed: {

    }
})
</script>
