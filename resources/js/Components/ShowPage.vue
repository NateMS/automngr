<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="py-6 grid grid-cols-12 gap-12 max-w-7xl sm:px-6 lg:px-8">
            <div class="col-span-6 xs:col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="col-span-3 xs:col-span-12">
                <div class="w-full flex flex-col">
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
        <div class="py-6 grid grid-cols-12 gap-8 w-full">
            <slot name="more"></slot>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'

export default {
    components: {
        Layout,
    },
}
</script>