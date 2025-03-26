<script setup>
import { useToast } from 'vue-toastification';
import { watch, computed } from 'vue';

const props = defineProps({
    errors: Object,
    flash: Object,
});

const toast = useToast();
if(props.flash.success){
  toast.success(props.flash.success)
}
// watch(() => props.flash.success, (newSuccessMessage) => {
//   if (newSuccessMessage) {
//     toast.success(newSuccessMessage); // Display success message as a toast
//   }
// });

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
