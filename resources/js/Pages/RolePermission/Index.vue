<script setup>
import { ref, watch  } from 'vue';
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import PaginationLinks from '../../Components/PaginationLinks.vue'
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';


// Props passed from Laravel controller
const props = defineProps({
    roles: Object,
    searchQuery: String, // Search query from the backend
});

// Accordion state
const openAccordion = ref(null);
// Toggle accordion
const toggleAccordion = (groupname) => {
  openAccordion.value = openAccordion.value === groupname ? null : groupname;
};
// Check if accordion is open
const isOpen = (groupname) => {
  return openAccordion.value === groupname;
};



// Search functionality
const searchQuery = ref(props.searchQuery || '');

// Watch for changes in searchQuery and update the URL
watch(searchQuery, (newQuery) => {
  router.visit(route('rolepermission.index', { search: newQuery }), {
    preserveState: true, // Preserve the current page state
    replace: true, // Replace the current history state
  });
});

// Function to reload roles (reset search)
const reloadRoles = () => {
  searchQuery.value = ''; // Clear the search query
  router.visit(route('rolepermission.index'), {
    preserveState: true, // Preserve the current page state
    replace: true, // Replace the current history state
  });
};

const toast = useToast();
const form = useForm({});

const deleteRole = (roleId, rolename) => {
    if (!confirm('Are you sure you want to delete this role?')) return;

    if (rolename === 'superadmin') {
        // Superadmin role shouldn't be deleted
        toast.error('You cannot delete the superadmin role!');
    } else {
        // Send the delete request to the backend
        form.delete(`/rolepermission/${roleId}`, {
            onSuccess: (response) => {
                // Success response handling
                toast.success(response.success || 'Role deleted successfully!');
            },
            onError: (errors) => {
                // Error handling
                toast.error(errors.error || 'Failed to delete role. Please try again.');
            },
        });
    }
};


</script>

<template>
    <Title>
        <!-- Create Button Section -->
        <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
            <Link 
                :href="route('rolepermission.create')" 
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
                <label for="search-role" class="sr-only">Search by role name</label>
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
        
        <div class="rounded-md border border-stroke bg-white p-5 shadow-md dark:border-strokedark dark:bg-boxdark sm:p-7">
            <div class="flex flex-col">
                <!-- Header -->
                <div class="grid grid-cols-1 sm:grid-cols-4 bg-gray-100 dark:bg-meta-4 p-4 rounded-md text-center font-semibold">
                    <div>SL/No</div>
                    <div>Role Name</div>
                    <div>Permission Name</div>
                    <div>Action</div>
                </div>

                <!-- Role List -->
                <div
                    v-for="(role, index) in roles.data"
                    :key="role.roleid"
                    class="grid grid-cols-1 sm:grid-cols-4 items-center border-b p-4 text-center text-gray-800 dark:text-white"
                >
                    <!-- Serial Number -->
                    <div class="p-2">{{ index + 1 }}</div>

                    <!-- Role Name -->
                    <div class="p-2 font-medium">{{ role.rolename }}</div>

                    <!-- Permissions Accordion -->
                    <div class="p-2">
                        <div v-for="group in role.groups" :key="group.groupname" class="mb-2">
                            <button
                                @click="toggleAccordion(group.groupname + index)"
                                class="w-full flex justify-between items-center px-4 py-2 bg-gray-600 text-white rounded-md shadow-md transition hover:bg-gray-700"
                            >
                                <span class="font-medium">{{ group.groupname }}</span>
                                <svg
                                    class="w-5 h-5 transition-transform transform"
                                    :class="{ 'rotate-180': isOpen(group.groupname + index) }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Permission List -->
                            <div v-show="isOpen(group.groupname + index)" class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md shadow-md">
                                <ul>
                                    <li
                                        v-for="permission in group.permissions"
                                        :key="permission.name"
                                        class="py-1 border-b last:border-b-0 text-sm"
                                    >
                                        {{ permission.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-2">
                        <a
                        :href="route('rolepermission.edit', role.roleid)"
                        class="inline-flex justify-center py-2 px-4 mr-1 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                        Edit
                        </a>
                        <button 
                            @click="deleteRole(role.roleid,role.rolename)" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <!-- Pagination Links -->
            <div class="mt-8">
                <PaginationLinks :paginator="roles"/>
            </div>
        </div>
    </Container>
</template>