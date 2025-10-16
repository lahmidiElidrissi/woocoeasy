<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3'
import DataTablesCore from 'datatables.net'
import 'datatables.net-dt'
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products'
    },
];

const columns = [
    {
        data: 'id',
        orderable: false,
        searchable: false,
        render: function (data: any) {
            return `<input type="checkbox" 
                class="dark:border-slate-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 
                dark:checked:scale-100 w-4 h-4 bg-gray-100" value="${data}" />`;
        }
    },
    { data: 'name', title: 'Name' },
    { data: 'price', title: 'Price' },
];

const options = {
    responsive: true,
    serverSide: true,
    processing: true,
    ajax: '/datatables/products',
    columnDefs: [
        {
            targets: 0,
            orderable: false,
            searchable: false,
        }
    ]
};

defineProps({});

DataTable.use(DataTablesCore)
</script>

<template>

    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-[80%] mx-auto p-4">
            <div class="flex justify-center flex-wrap">
                <h4 class="text-2xl">Products :</h4>
                <Button class="ml-auto">
                    <a href="/products/create">Add Product</a>
                </Button>
            </div>
            <hr class="my-5">
            <DataTable :columns="columns" :options="options" class="display">
                <thead>
                    <tr>
                        <th>
                          <input type="checkbox" 
                          class="dark:border-slate-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 
                          dark:checked:scale-100 w-4 h-4 bg-gray-100" />
                        </th>
                        <th>Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
            </DataTable>
        </div>

    </AppLayout>
</template>

<style>
@import 'datatables.net-dt';
</style>