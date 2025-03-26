<script setup>
import { ref } from "vue";
import NavLink from "../Components/NavLink.vue";

const openDropdowns = ref({});

const toggleDropdown = (key) => {
  openDropdowns.value[key] = !openDropdowns.value[key];
};
</script>
<template>
     <aside
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
      aria-label="Sidenav"
      id="drawer-navigation"
    >
      <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">

        <ul class="space-y-2">
          <li>
            <NavLink routeName="dashboard" componentName="Home">Home</NavLink>
          </li>

          <!-- Master Setup Dropdown -->
          <li>
            <button
              type="button"
              @click="toggleDropdown('masterSetup')"
              class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>

              <span class="flex-1 ml-3 text-left whitespace-nowrap">Master Setup</span>
              <svg
                aria-hidden="true"
                class="w-6 h-6 transition-transform"
                :class="{ 'rotate-180': openDropdowns.masterSetup }"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <ul v-show="openDropdowns.masterSetup" class="py-2 space-y-2 transition-all duration-300">
              <li>
                <Link :href="route('branch.index')" class="flex gap-2 items-center p-2 pl-5 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path d="M13 4.5a2.5 2.5 0 1 1 .702 1.737L6.97 9.604a2.518 2.518 0 0 1 0 .792l6.733 3.367a2.5 2.5 0 1 1-.671 1.341l-6.733-3.367a2.5 2.5 0 1 1 0-3.475l6.733-3.366A2.52 2.52 0 0 1 13 4.5Z" />
                  </svg> Branch Master
                </Link>
              </li>
              <li>
                <Link :href="route('rolepermission.index')" class="flex gap-2 items-center p-2 pl-5 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                  Users Role
                </Link>
              </li>
            </ul>
          </li>

          <!-- Role Dropdown -->
          <li>
            <button
              type="button"
              @click="toggleDropdown('role')"
              class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5" />
            </svg>
              <span class="flex-1 ml-3 text-left whitespace-nowrap">Roles</span>
              <svg
                aria-hidden="true"
                class="w-6 h-6 transition-transform"
                :class="{ 'rotate-180': openDropdowns.role }"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <ul v-show="openDropdowns.role" class="py-2 space-y-2 transition-all duration-300">
              <li>
                <Link :href="route('listing.index')" class="flex gap-2 items-center p-2 pl-5 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                  Listing
                </Link>
              </li>
              <li>
                <Link :href="route('rolepermission.index')" class="flex gap-2 items-center p-2 pl-5 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                  Users Role
                </Link>
              </li>
              <li>
                <Link :href="route('register.index')" class="flex gap-2 items-center p-2 pl-5 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                  Permissions
                </Link>
              </li>
            </ul>
          </li>
        </ul>

       
      </div>
      
    </aside>
</template>