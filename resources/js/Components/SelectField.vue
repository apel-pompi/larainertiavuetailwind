<script setup>
import { ref, defineProps } from "vue";
import { NSelect } from "naive-ui";


const model = defineModel();

const props = defineProps({
  roles: Array
})

const selected = ref([]);

</script>

<template>
  <div>
    <label>Select an option:</label>
    <NSelect 
      v-model="model"
      :options="props.roles.map(role => ({ label: role.name, value: role.id }))"
      placeholder="Choose a role"
      multiple/>
  </div>
</template>
