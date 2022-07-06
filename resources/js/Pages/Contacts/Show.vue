<template>
    <show-page>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                {{ contact.title }}
            </h2>
        </template>

        <template #info>
            <small-title title="Kontakt" class="mb-3" />
            <div v-if="contact.deleted_at" class="col-span-12 bg-red-500 p-2 -mb-1 text-white rounded-t-md font-bold">
                Kontakt gelöscht am {{ contact.deleted_at }}
            </div>
            <contact-card :contact="contact" />
        </template>

        <template #actions>
            <edit-button v-if="!contact.deleted_at" :href="route('contacts.edit', contact.id)" />
            <letter-button v-if="!contact.deleted_at" :href="route('contacts.letter', contact.id)" />
            <delete-button v-if="!contact.deleted_at" :href="route('contacts.destroy', contact.id)" />
            <restore-button v-if="contact.deleted_at" :href="route('contacts.restore', contact.id)" />
            <div v-if="contact.deleted_at">
                gelöscht: {{ contact.deleted_at }}
            </div>
        </template>

        <template #more>
            <contract-table
                :contracts="contact.buy_contracts"
                :type="0"
                :contactId="contact.id"
                :show_upload="!contact.deleted_at"
                :title="contact.buy_contracts.length > 1 ? contact.buy_contracts.length + ' Ankaufsverträge' : 'Ankaufsvertrag'"
            />
            <contract-table
                :contracts="contact.sell_contracts"
                :type="1"
                class="mt-10"
                :contactId="contact.id"
                :show_upload="!contact.deleted_at"
                :title="contact.sell_contracts.length > 1 ? contact.sell_contracts.length + ' Verkaufsverträge' : 'Verkaufsvertrag'"
            />
            <documents-view class="mt-5" :initial_documents="contact.documents" :id="contact.id" documentable_type="contacts" :show_upload="!contact.deleted_at" />
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import ContactCard from '@/Components/ContactCard.vue';
import EditButton from '@/Components/Buttons/EditButton.vue';
import LetterButton from '@/Components/Buttons/LetterButton.vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import RestoreButton from '@/Components/Buttons/RestoreButton.vue';
import ContractTable from '../../Components/Contracts/ContractTable.vue';
import SmallTitle from '../../Components/SmallTitle.vue';
import DocumentsView from '@/Components/Documents/View.vue';

export default {
  components: {
    ShowPage,
    BreadCrumb,
    ContactCard,
    EditButton,
    LetterButton,
    DeleteButton,
    RestoreButton,
    ContractTable,
    SmallTitle,
    DocumentsView,
  },

  props: {
    contact: Object,
  },
};
</script>
