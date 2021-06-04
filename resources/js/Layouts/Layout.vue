<template>
    <div class="leading-normal tracking-normal" id="main-body">
        <div class="flex flex-wrap">   
            <Sidebar />
            <div class="w-full bg-gray-100 pl-0 lg:pl-64 min-h-screen" :class="sideBarOpen ? 'overlay' : ''" id="main-content">
                <Navbar>
                  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <slot name="header"></slot>
                  </h2>
                </Navbar>
                <jet-banner class="sticky top-20 z-40" />
                <div class="pt-12 px-12 bg-gray-100 mb-20">
                    <main>
                        <slot></slot>
                    </main>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Sidebar from "@/Components/Sidebar"
import Navbar from "@/Components/Navbar"
import JetBanner from '@/Jetstream/Banner'

export default {
  computed: {
    ...mapState(['sideBarOpen'])
  },
  components: {
    Sidebar,
    Navbar,
    JetBanner,
  }
}
</script>