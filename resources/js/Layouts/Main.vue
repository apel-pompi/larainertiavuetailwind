<script setup>

import { initFlowbite } from 'flowbite'

import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";

import { useToast } from 'vue-toastification';

import { ref, onMounted } from "vue";



const show = ref(false);


const props = defineProps({
    flash: Object,
});

const toast = useToast();


if(props.flash.success){
    toast.success(props.flash.success)
}else{}



onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <!-- overlay -->
    <div v-show="show" @click="show = false" class="fixed inset-0 z-40"></div>
    <!-- Nav Bar -->
    <Navbar/>
    <!-- Sidebar -->
    <Sidebar/>
      

    <main class="p-4 md:ml-64 h-auto pt-20">
        <slot />
    </main>
</template>