<script setup>
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import PaginationLinks from '../../Components/PaginationLinks.vue'
</script>

<template>
    <Title>
        <!-- Create Button Section -->
        <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
            <Link 
                :href="route('branch.create')" 
                class="flex items-center gap-2 px-6 py-2 rounded-lg bg-slate-950 text-white transition-all duration-300 hover:bg-slate-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-sm sm:text-base"
                :disabled="isCreatingDisabled"
                aria-label="Create Role"
            >
                <i class="fa-solid fa-plus"></i> 
                <span>Create</span>
            </Link>
        </div>

        <!-- Search & Reload Section -->
        <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
            <!-- Search Input -->
            <div class="flex-2">
                <label for="search-role" class="sr-only">Search by branch name</label>
                <input
                    type="text"
                    id="search-role"
                    v-model="searchQuery"
                    placeholder="Search by role name"
                    class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                />
            </div>

            <!-- Reload Button -->
            <Link 
                @click="reloadRoles"
                class="flex items-center justify-center gap-2 w-full ml-4 sm:w-auto px-6 py-2 rounded-lg bg-slate-950 text-white transition-all duration-300 hover:bg-slate-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-sm sm:text-base"
                :disabled="isReloading"
                aria-label="Reload Roles"
            >
                <i class="fa-solid fa-rotate-right"></i> 
                <span>Reload</span>
            </Link>
        </div>
    </Title>
    <Container>
        <div class="flex flex-col">
            
        </div>
    </Container>
</template>