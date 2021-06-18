<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="grid grid-cols-12 gap-12 mb-8">
            <div class="xl:col-span-6 col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="xl:col-span-3 xl:col-end-13 col-span-12">
                <div class="w-full flex flex-col">
                    <small-title title="Aktionen" class="mb-3" />
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-12">
            <slot name="more"></slot>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import SmallTitle from './SmallTitle.vue';

export default {
  components: {
    Layout,
    SmallTitle,
  },
};
</script>
