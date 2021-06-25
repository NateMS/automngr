<template>
    <show-page>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                {{ car.name }}
            </h2>
        </template>
        <template #info>
            <small-title title="Auto" class="mb-3" />
            <car-card :car="car" />
        </template>
        <template #actions>
            <edit-button v-if="!car.deleted_at" :href="route('cars.edit', car.id)" />
            <delete-button v-if="!car.deleted_at" :href="route('cars.destroy', car.id)" />
            <restore-button v-if="car.deleted_at" :href="route('cars.restore', car.id)" />
            <div v-if="car.deleted_at">
                gelöscht: {{ car.deleted_at }}
            </div>
        </template>
        <template #more>
            <contract-table
                :contracts="car.buy_contracts"
                :type="0"
                :carId="car.id"
                :show_upload="!car.deleted_at"
                :title="car.buy_contracts.length > 1 ? car.buy_contracts.length + ' Ankaufsverträge' : 'Ankaufsvertrag'"
            />
            <contract-table
                :contracts="car.sell_contracts"
                :type="1"
                :carId="car.id"
                class="mt-10"
                :show_upload="!car.deleted_at"
                :title="car.sell_contracts.length > 1 ? car.sell_contracts.length + ' Verkaufsverträge' : 'Verkaufsvertrag'"
            />
            <documents-view class="mt-5" :initial_documents="car.documents" :id="car.id" documentable_type="cars" :show_upload="!car.deleted_at" />
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import CarCard from '@/Components/CarCard.vue';
import EditButton from '@/Components/Buttons/EditButton.vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import RestoreButton from '@/Components/Buttons/RestoreButton.vue';
import ContractTable from '../../Components/Contracts/ContractTable.vue';
import SmallTitle from '../../Components/SmallTitle.vue';
import DocumentsView from '@/Components/Documents/View.vue';

export default {
  components: {
    ShowPage,
    BreadCrumb,
    CarCard,
    EditButton,
    DeleteButton,
    RestoreButton,
    ContractTable,
    SmallTitle,
    DocumentsView,
  },
  props: {
    car: Object,
  },
};
</script>
