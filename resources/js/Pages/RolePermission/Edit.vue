<script setup>
import { ref, watch } from "vue";
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import InputField from "../../Components/InputField.vue";
import CheckBox from "../../Components/CheckBox.vue";
import PrimaryBtn from "../../Components/PrimaryBtn.vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from 'vue-toastification';

const props = defineProps({
    items: Array,
    role: Object
});

const toast = useToast();

const form = useForm({
    rolename: props.role[0].rolename,
    checkedPermissions: props.role[0].groups.flatMap(group => 
        group.permissions.map(permission => permission.id)
    )
});

const checkAll = ref();

// Reactive state to store submitted data
const submittedData = ref(null);

// Function to handle "Check All" checkbox change
const toggleCheckAll = () => {
  if (checkAll.value) {
    // Check all permissions
    form.checkedPermissions = props.items.flatMap(group =>
      group.permissions.map(permission => permission.id)
    );
  } else {
    // Uncheck all permissions
    form.checkedPermissions = [];
  }
};

// Function to handle group checkbox change

const toggleGroup = (groupId) => {
  const group = props.items.find(group => group.id === groupId);
  const groupPermissionIds = group.permissions.map(permission => permission.id);
  const allGroupPermissionsChecked = groupPermissionIds.every(id =>
    form.checkedPermissions.includes(id)
  );

  if (allGroupPermissionsChecked) {
    // Uncheck all permissions in the group
    form.checkedPermissions = form.checkedPermissions.filter(
      id => !groupPermissionIds.includes(id)
    );
  } else {
    // Check all permissions in the group
    form.checkedPermissions = [
      ...new Set([...form.checkedPermissions, ...groupPermissionIds]),
    ];
  }
};

// Watch for changes in checkedPermissions to update the "Check All" checkbox

watch(
  () => form.checkedPermissions,
  (newVal) => {
    const allPermissionIds = props.items.flatMap(group =>
      group.permissions.map(permission => permission.id)
    );
    checkAll.value = newVal.length === allPermissionIds.length;
  }
);

// Function to handle form submission
const submitForm = () => {
   
  if (!form.rolename.trim()) {
    toast.error('Please enter role name.');
    return; 
  }

  
  if (form.checkedPermissions.length === 0) {
    toast.error('Please select at least one permission.');
    return; 
  }

  
  form.put(`/rolepermission/${props.role[0].roleid}`, {
    onSuccess: (response) => {
      
      submittedData.value = form.data();
      
      toast.success('Permission update successfully!');
    },
    onError: (errors) => {
      
      toast.error('Permission update failed. Please try again.');
    },
  });
};

</script>

<template>
    <Title>
      <div class="flex justify-between items-center px-7 xl:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark">
        <Link :href="route('rolepermission.index')" class="px-6 py-2 rounded-lg bg-slate-950 text-white disabled:bg-slate-300 disabled:cursor-wait"><i class="fa-solid fa-arrow-left"></i> Back</Link>
      </div>
      
    </Title>
    <Container>
        
        <div class="rounded-md border border-stroke bg-white p-5 shadow-md dark:border-strokedark dark:bg-boxdark sm:p-7">
            <form @submit.prevent="submitForm">
                <!-- Role Name -->
                <InputField
                    label="Role Name"
                    icon="heading"
                    placeholder="Enter Role Name"
                    v-model="form.rolename"
                />
                <div class="justify-center flex pt-5">
                     <!-- Check All Checkbox -->
                     <CheckBox name="allcheck" @change="toggleCheckAll" v-model="checkAll"> All Permission </CheckBox>
                                         
                </div>

                <div v-if="items.length" class="grid grid-cols-1 gap-9 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-10">
                    <div v-for="group in items" :key="group.id" class="flex flex-col gap-9">
                        <div class="mb-10 bg-white dark:bg-boxdark text-center border border-stroke dark:border-strokedark">
                            <!-- Group Checkboxes -->
                                
                                <div class="justify-center flex p-3 mb-3 border-b border-stroke dark:border-strokedark">
                                    
                                    <CheckBox :name="'group' + group.id" @change="toggleGroup(group.id)" :value="'group' + group.id">{{ group.name }}</CheckBox>
                                    
                                </div>
                                <!-- Individual Permission Checkboxes -->
                                <CheckBox v-for="permission in group.permissions" :key="permission.id" v-model="form.checkedPermissions" :value="permission.id" :name="'name' + permission.id">{{ permission.name }}</CheckBox>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="justify-center flex">
                    <PrimaryBtn :disabled="form.processing">Update</PrimaryBtn>
                </div>
            </form>
        </div>
    </Container>
</template>
