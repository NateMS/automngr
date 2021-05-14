<template>
    <div class="sticky top-0 z-40">
            <div class="w-full h-20 px-6 bg-gray-100 border-b flex items-center justify-between">

              <!-- left navbar -->
              <div class="flex">

                <!-- mobile hamburger -->
                <div class="inline-block lg:hidden flex items-center mr-4">
                  <button class="hover:text-blue-500 hover:border-white focus:outline-none navbar-burger" @click="toggleSidebar()">
                    <svg class="h-5 w-5" v-bind:style="{ fill: 'black' }" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                  </button>
                </div>
                <slot></slot>
              </div>

              <!-- right navbar -->
              <div class="flex items-center relative">
                <jet-dropdown align="right" width="48">
                  <template #trigger>
                      <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                          <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                      </button>

                      <span v-else class="inline-flex rounded-md">
                          <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                              {{ $page.props.user.name }}

                              <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                              </svg>
                          </button>
                      </span>
                  </template>

                  <template #content>
                      <!-- Account Management -->
                      <div class="block px-4 py-2 text-xs text-gray-400">
                          Account verwalten
                      </div>

                      <jet-dropdown-link :href="route('profile.show')">
                          Profil
                      </jet-dropdown-link>

                      <div class="border-t border-gray-100"></div>

                      <!-- Authentication -->
                      <form @submit.prevent="logout">
                          <jet-dropdown-link as="button">
                              Abmelden
                          </jet-dropdown-link>
                      </form>
                  </template>
              </jet-dropdown>
              </div>
            </div>
    </div>
</template>

<script>
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import { mapState } from 'vuex'
export default {
   components: {
          JetDropdown,
          JetDropdownLink,
      },

    computed: {
        ...mapState(['sideBarOpen'])
    },
    data() {
        return {
            dropDownOpen: false
        }
    },
    methods: {
        toggleSidebar() {
            this.$store.dispatch('toggleSidebar')
        }
    }
}
</script>