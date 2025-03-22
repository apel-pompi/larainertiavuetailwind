<script setup>
import UpdateInfo from './Sections/UpdateInfo.vue';
import UpdatePassword from './Sections/UpdatePassword.vue';
import DeleteAccount from './Sections/DeleteAccount.vue';
defineProps({
    user:Object,
    status:String
})
</script>
<template>
    <div class="grid grid-cols-2 gap-4 mb-4 overflow-hidden">
        <div class="rounded-lg">
            <UpdateInfo :user="user" :status="status"/>
        </div>
        <div class="rounded-lg">
            <UpdatePassword />
        </div>
    </div>
    <div class="rounded-lg">
        <DeleteAccount />
    </div>
</template>

