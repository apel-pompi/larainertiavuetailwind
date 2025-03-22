<script setup>
import Container from "../../Components/Container.vue";
import Card from '../../Components/Card.vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'
import InputField from "../../Components/InputField.vue";
import breadcrumbs from '../../Components/breadcrumbs.vue'
import { router, useForm } from "@inertiajs/vue3";

const params = route().params;

const props = defineProps({
    listings:Object,
    searchTerm:String,
})

const username = params.user_id ? props.listings.data.find(i => i.user_id === Number(params.user_id)).user.name:null;

const form = useForm({
    search: props.searchTerm
});


const search = () => {
    router.get(route("listing.index"), { 
        search: form.search,
        user_id: params.user_id, 
        tag:params.tag
    });
};

const selectTag = (tag) => {
    router.get(route("listing.index"),{
        user_id: params.user_id, 
        search: params.search,
        tag:tag
    })
}

</script>

<template>
    <Container>
        <breadcrumbs>
            <div class="flex justify-between px-7 py-7 border-b last:border-b-0 border-r last:border-r-0 border-stroke dark:border-strokedark xl:border-b-0 [&>*:nth-child(3)]:sm:border-b-0">
                <Link :href="route('listing.create')" class="px-6 py-2 rounded-lg bg-slate-950 text-white disabled:bg-slate-300 disabled:cursor-wait"><i class="fa-solid fa-plus"></i> Create</Link>
            </div>
        </breadcrumbs>
    
    <!-- search filter -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <Link 
                v-if="params.tag" :href="route('listing.index',{...params, tag:null,page:null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
                >
                {{ params.tag }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link 
                v-if="params.search" :href="route('listing.index',{...params, search:null,page:null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
                >
                {{ params.search }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
            <Link 
                v-if="params.user_id" :href="route('listing.index',{...params, user_id : null, page : null})"
                class="px-2 py-1 rounded-md bg-indigo-500 text-white flex items-center gap-2"
                >
                {{ username }}
                <i class="fa-solid fa-xmark"></i>
            </Link>
        </div>
        
        <div class="w-1/4">
            <form @submit.prevent="search">
                <InputField type="search" label="" icon="magnifying-glass" placeholder="Search..." v-model="form.search"
                />
            </form>
        </div>
    </div>
    <!-- content -->
    <div v-if="Object.keys(listings.data).length">
        <div class="grid grid-cols-3 gap-4">
            <div v-for="listing in listings.data" :key="listings.id">
                <Card :listing="listing"></Card>
            </div>
        </div>
        <div class="mt-8">
            <PaginationLinks :paginator="listings"/>
        </div>
    </div>
    <div v-else>There are no listings</div>
</Container>
</template>