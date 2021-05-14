<template>
    <div class="leading-normal tracking-normal" id="main-body">
        <div class="flex flex-wrap">   
            <Sidebar />
            <div class="w-full bg-gray-100 pl-0 lg:pl-64 min-h-screen" :class="sideBarOpen ? 'overlay' : ''" id="main-content">
                <Navbar>
                    <slot name="header"></slot>
                </Navbar>
                <div class="p-6 bg-gray-100 mb-20">
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

export default {
  computed: {
    ...mapState(['sideBarOpen'])
  },
  components: {
    Sidebar,
    Navbar,
  }
}
</script>