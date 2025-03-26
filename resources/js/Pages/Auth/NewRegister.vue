<script setup>
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Array,
});

</script>

<template>
    <Title>
      <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
        <Link :href="route('register.create')" class="px-6 py-2 rounded-lg bg-slate-950 text-white disabled:bg-slate-300 disabled:cursor-wait"><i class="fa-solid fa-plus"></i> Create</Link>
      </div>
      
    </Title>
    <Container>
        <div class="rounded-md border border-stroke bg-white p-5 shadow-md dark:border-strokedark dark:bg-boxdark sm:p-7">
            <div class="flex flex-col">
                <!-- Header -->
                <div class="grid grid-cols-5 sm:grid-cols-5 bg-gray-100 dark:bg-meta-5 p-4 rounded-md text-center font-semibold">
                    <div>SL/No</div>
                    <div>User Name</div>
                    <div>Email</div>
                    <div>Role Name</div>
                    <div>Action</div>
                </div>
                <div 
                    v-for="(users,index) in user"
                    :key="users.id"
                    class="grid grid-cols-5 sm:grid-cols-5 items-center border-b p-4 text-center text-gray-800 dark:text-white">
                    <div class="p-2">{{ index + 1 }}</div>
                    <div class="p-2">{{ users.username }}</div>
                    <div class="p-2">{{ users.email }}</div>
                    <div class="p-2">
                        <span v-for="role in users.roles" :key="role" class="px-2 mx-2 py-1 text-sm font-medium text-white bg-blue-500 rounded-md">
                            {{ role }}
                        </span>
                    </div>
                    <div class="p-2">
                        <Link :href="route('register.edit', users.id)" class="inline-flex justify-center py-2 px-4 mr-1 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Edit
                        </Link>

                    </div>
                </div>
            </div>
        </div>       
    </Container>
</template>