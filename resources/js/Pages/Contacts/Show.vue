<template>
    <show-page>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                {{ contact.title }}
            </h2>
        </template>

        <template #info>
            <contact-card :contact="contact" />
        </template>

        <template #actions>
            <edit-button v-if="!contact.deleted_at" :href="route('contacts.edit', contact.id)" />
            <delete-button v-if="!contact.deleted_at" :href="route('contacts.destroy', contact.id)" />
            <restore-button v-if="contact.deleted_at" :href="route('contacts.restore', contact.id)" />
            <div v-if="contact.deleted_at">
                gelöscht: {{ contact.deleted_at }}
            </div>
        </template>
   
        <template #more>
            <div class="col-span-10 xs:col-span-12">
                <div class="whitespace-nowrap">
                    <h1 class="font-bold text-3xl">{{ contact.buy_contracts.total > 1 ? contact.buy_contracts.total + ' Ankaufsverträge' : 'Ankaufsvertrag' }}</h1>
                </div>
                <div v-for="contract in contact.buy_contracts.data" :key="contract.id">
                    <buy-contract-card :contract="contract"/>
                </div>
                <div v-if="!contact.deleted_at">
                    <inertia-link :href="route('contracts.create_from_contact', [0, contact.id])" class="w-full py-6 mt-12 inline-flex items-center px-4 bg-green-800 border border-transparent rounded-md font-semibold justify-center text-md text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition" >
                        <unicon fill="white" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
                        Neuer Ankaufsvertrag
                    </inertia-link>
                </div>
            </div>
            <div class="col-span-10 xs:col-span-12">
                <div class="whitespace-nowrap">
                    <h1 class="font-bold text-3xl">{{ contact.sell_contracts.total > 1 ? contact.sell_contracts.total + ' Verkaufsverträge' : 'Verkaufsvertrag' }}</h1>
                </div>
                <div v-for="contract in contact.sell_contracts.data" :key="contract.id">
                    <sell-contract-card :contract="contract"/>
                </div>
                <div v-if="!contact.deleted_at">
                    <inertia-link :href="route('contracts.create_from_contact', [1, contact.id])" class="py-6 w-full mt-12 inline-flex items-center px-4 bg-green-800 border border-transparent rounded-md font-semibold justify-center text-md text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition" >
                        <unicon fill="white" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
                        Neuer Verkaufssvertrag
                    </inertia-link>
                </div>
            </div>
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContactCard from '@/Components/ContactCard.vue'
import BuyContractCard from '@/Components/BuyContractCard.vue'
import SellContractCard from '@/Components/SellContractCard.vue'
import EditButton from '@/Components/Buttons/EditButton.vue'
import DeleteButton from '@/Components/Buttons/DeleteButton.vue'
import RestoreButton from '@/Components/Buttons/RestoreButton.vue'

export default {
    components: {
        ShowPage,
        BreadCrumb,
        ContactCard,
        BuyContractCard,
        SellContractCard,
        EditButton,
        DeleteButton,
        RestoreButton,
    },

    props: {
        contact: Object,
    },
    data() {
        return {
            currentRoute: 'contacts.show',
        }
    },
}
</script>