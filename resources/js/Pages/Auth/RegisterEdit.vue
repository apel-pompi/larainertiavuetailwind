<script setup>
    import Container from '../../Components/Container.vue';
    import Title from '../../Components/Title.vue';
    import InputField  from '../../Components/InputField.vue';
    import PrimaryBtn  from '../../Components/PrimaryBtn.vue';
    import ErrorsMessage from '../../Components/ErrorMessages.vue';
    import { NSelect } from "naive-ui";
    import { useForm } from '@inertiajs/vue3';
    import { useToast } from 'vue-toastification';
    import { defineProps, onMounted } from 'vue';

    const props = defineProps({
        roles: Array,
        user:Object,
        errors: Object,
        flash: Object,
    })
    const toast = useToast();
    const form = useForm({
        name: props.user.name,
        username: props.user.username,
        email: props.user.email,
        password:"",
        password_confirmation:"",
        selectedrole:props.user.roles.map(role => role),
         _method:'POST'
    })
    
    onMounted(() => {
        if (props.flash?.success) {
            toast.success(props.flash.success);
        }
        if (props.flash?.error) {
            toast.error(props.flash.error);
        }
    });

</script>
<template>
     <Title>
      <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
        <Link :href="route('register.index')" class="px-6 py-2 rounded-lg bg-slate-950 text-white disabled:bg-slate-300 disabled:cursor-wait"><i class="fa-solid fa-plus"></i> Manage</Link>
      </div>
    </Title>
    <Container>
        <ErrorsMessage :errors="errors" :flash="flash" />
        <div class="flex justify-center items-center rounded-md border border-stroke bg-white pt-2 shadow-md dark:border-strokedark dark:bg-boxdark sm:p-7 min-h-screen ">
            <div class="rounded-md border border-stroke bg-white p-5 dark:border-strokedark dark:bg-boxdark sm:p-7 w-full max-w-lg">
                <h2 class="text-2xl font-semibold text-center text-gray-900 dark:text-white">Create an Account</h2>
                <div class="bg-white dark:bg-gray-800 rounded-lg px-6 pb-6 pt-0 sm:px-8 sm:pb-8 sm:pt-0">

                    <form @submit.prevent="form.post(route('register.update',props.user.id))" class="space-y-6 mt-4">

                        <InputField label="Name" icon="id-badge" v-model="form.name" />
                        <InputField label="Username" icon="user" v-model="form.username" />
                        <InputField label="Email" type="email" icon="at" v-model="form.email" />
                        <InputField label="Password" type="password" icon="key" v-model="form.password" />
                        <InputField label="Confirm Password" type="password" icon="key" v-model="form.password_confirmation" />
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Select an option:</label>
                            <NSelect 
                                v-model:value="form.selectedrole"
                                :options="Object.entries(props.roles).map(([name, id]) => ({ label: name, value: id }))"
                                placeholder="Choose a role"
                                multiple
                                class="w-full"/>
                        </div>

                        <PrimaryBtn :disabled="form.processing">Register</PrimaryBtn>
                    </form>
                </div>
            </div>
        </div>
    </Container>
</template>