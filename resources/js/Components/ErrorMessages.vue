<script setup>
import { useToast } from 'vue-toastification';
import { watch, computed } from 'vue';

const props = defineProps({
    errors: Object,
});

const toast = useToast();

// Show toast notifications for errors dynamically
watch(() => props.errors, (newErrors) => {
    if (Object.keys(newErrors).length > 0) {
        Object.values(newErrors).forEach(errorMessages => {
            if (Array.isArray(errorMessages)) {
                errorMessages.forEach(message => toast.error(message));
            } else {
                toast.error(errorMessages);
            }
        });
    }
}, { deep: true });





</script>

<template>
    
</template>
