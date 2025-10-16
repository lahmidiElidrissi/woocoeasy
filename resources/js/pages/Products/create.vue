
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const form = useForm({
    name: '',
    description: '',
    price: 0,
});

defineProps<{}>();
</script>



<template>

    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col items-center justify-center h-screen dark">
                <div class="w-full max-w-md bg-gray-900 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-200 mb-4">Create new product</h2>

                    <form class="flex flex-col" @submit.prevent="form.post('/products')">
                    <input v-model="form.name" placeholder="Product name" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="text">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.name">{{ form.errors.name }}</div>

                    <input v-model="form.description" placeholder="Product description" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="text">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.description">{{ form.errors.description }}</div>

                    <input v-model="form.price" placeholder="Enter your email address" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="number">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.price">{{ form.errors.price }}</div>

                    <button class="bg-gradient-to-r from-gray-500 to-blue-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-green-600 hover:to-blue-600 transition ease-in-out duration-150" type="submit" :disabled="form.processing">Create</button>
                    </form>
                </div>
            </div>
    </AppLayout>

</template>
