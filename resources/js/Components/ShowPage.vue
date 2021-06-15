<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="grid grid-cols-12 gap-12 mb-8">
            <div class="xl:col-span-6 sm:col-span-8 col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="xl:col-span-3 sm:col-span-4 col-span-12">
                <div class="w-full flex flex-col">
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

export default {
  components: {
    Layout,
  },
};
</script>
