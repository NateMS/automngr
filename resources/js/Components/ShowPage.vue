<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="grid grid-cols-12 xl:gap-12 md:gap-6 sm:gap-4 gap-3 xl:mb-8 mb-3 max-w-5xl mx-auto">
            <div class="xl:col-span-6 sm:col-span-8 col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="xl:col-span-3 xl:col-end-13 sm:col-span-4 xs:col-span-4 col-span-12">
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
