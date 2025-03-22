<script setup>
const model = defineModel();

defineProps({
    name: String,
    value: String,
});
</script>

<template>
    <div class="flex items-center gap-2 p-1 pl-5">
        <input
            type="checkbox"
            v-model="model"
            :id="name"
            :value="value"
            
        />
        <label
            :for="name"
            class="block font-medium text-slate-700 dark:text-slate-300 cursor-pointer text-title-sm"
        >
            <slot />
        </label>
    </div>
</template>