<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="grid grid-cols-12 gap-12 mb-8 max-w-5xl mx-auto">
            <div class="xl:col-span-9 col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="xl:col-span-3 xl:col-end-13 col-span-12">
                <div class="w-full flex flex-col">
                    <small-title title="Aktionen" class="mb-3" />
                    <slot name="actions"></slot>
                </div>
            </div>
            <div class="xl:col-span-9 col-span-12">
                <slot name="more"></slot>
            </div>
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
