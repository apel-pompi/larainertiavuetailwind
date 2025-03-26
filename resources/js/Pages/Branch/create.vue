<script setup>
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryBtn from "../../Components/PrimaryBtn.vue";
import ErrorMessages from "../../Components/ErrorMessages.vue"
import { useToast } from 'vue-toastification';
import { NSelect } from "naive-ui";
import { useForm } from "@inertiajs/vue3";
import { defineProps, onMounted } from 'vue';

const props = defineProps({
  errors: Object,
  flash: Object,
})

const toast = useToast();

const options = [
  { label: "Select Status", disabled: true },
  { label: "Active", value: "1" },
  { label: "Close", value: "0" }
];

const form = useForm({
    branchname: '',
    description: '',
    contacperson: '',
    designation: '',
    mailingaddress: '',
    emailaddress: '',
    phone: '',
    active: '',
});

onMounted(() => {
        if (props.flash.success) {
            toast.success(props.flash.success);
        }
        if (props.flash.error) {
            toast.error(props.flash.error);
        }
    });
console.log(props.flash.success)
</script>

<template>
    <Title>
        <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
            <Link :href="route('branch.index')" class="px-6 py-2 rounded-lg bg-slate-950 text-white disabled:bg-slate-300 disabled:cursor-wait"><i class="fa-solid fa-backward"></i> Manage</Link>
        </div>
    </Title>
    <Container>
        <div>
            <p v-if="flash.success">{{ flash.success }}</p>
        </div>
        <div class="mb-6">
            <form  @submit.prevent="form.post(route('branch.store'))" class="grid grid-cols-2 gap-6">
                <div class="space-y-6">
                    <InputField
                        label="Name"
                        icon="heading"
                        placeholder="Branch Name"
                        v-model="form.branchname"
                    />
                    <InputField
                        label="Designation"
                        icon="heading"
                        placeholder="Designation"
                        v-model="form.designation"
                    />
                </div>
                <div class="space-y-6">
                    <InputField
                        label="Description"
                        icon="heading"
                        placeholder="Branch Description"
                        v-model="form.description"
                    />
                    <InputField
                        label="Address"
                        icon="heading"
                        placeholder="Address"
                        v-model="form.mailingaddress"
                    />
                </div>
                <div class="space-y-6">
                    <InputField
                        label="Contact Person"
                        icon="heading"
                        placeholder="Contact Person"
                        v-model="form.contacperson"
                    />
                    <InputField
                        label="Phone"
                        icon="heading"
                        placeholder="Phone"
                        v-model="form.phone"
                    />
                </div>
                <div class="space-y-6">
                    <InputField
                        label="Email"
                        icon="heading"
                        placeholder="Email"
                        v-model="form.emailaddress"
                    />
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Select Status</label>
                        <NSelect 
                            v-model:value="form.active"
                            :options=options
                            placeholder="Select Status"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="text-right">
                    <PrimaryBtn :disabled="form.processing">Create</PrimaryBtn>
                </div>
            </form>
        </div>
    </Container>
</template>