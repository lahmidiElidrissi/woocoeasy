
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Dropzone from 'dropzone'
import 'dropzone/dist/dropzone.css'
import { ref, onBeforeUnmount, onMounted, watch } from 'vue';

const props = defineProps<{ product: { id: number; name: string; description: string; price: number; images: any[] } }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Edit',
        href: '#',
    },
];

Dropzone.autoDiscover = false;
const dropzoneRef = ref(null);
let dz : any = null;
const imageFiles = ref<File[]>([]);

onMounted(() => {
  if (dropzoneRef.value) {
    dz = new Dropzone(dropzoneRef.value, {
      url: '#',
      paramName: 'file',
      acceptedFiles: 'image/*',
      addRemoveLinks: true,
      autoProcessQueue: false,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    })
  }

  dz.on('addedfile', (file: any) => {
    imageFiles.value.push(file)
    form.images = [...imageFiles.value]
  })

  dz.on('removedfile', (file: any) => {
    imageFiles.value = imageFiles.value.filter(f => f !== file)
    form.images = [...imageFiles.value]
  })

  dz.on('error', (file: any, errorMessage: any) => {
    console.error('File error:', errorMessage)
  })
})

onBeforeUnmount(() => {
  if (dz) dz.destroy()
})

// Prepopulate form with product data
const form = useForm({
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    images: [] as File[],
});

const submitProductUpdate = () => {
  form.put(`/products/${props.product.id}`, { forceFormData: true });
}
</script>

<template>

    <Head title="Edit Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col items-center justify-center min-h-screen dark w-full">
                <div class="w-full max-w-lg bg-gray-900 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-200 mb-4">Edit Product</h2>

                    <form class="flex flex-col" @submit.prevent="submitProductUpdate">
                    <input v-model="form.name" placeholder="Product name" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="text">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.name">{{ form.errors.name }}</div>

                    <input v-model="form.description" placeholder="Product description" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="text">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.description">{{ form.errors.description }}</div>

                    <input v-model="form.price" placeholder="Price" class="bg-gray-700 text-gray-200 border-0 rounded-md p-2 mt-4 focus:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150" type="number">
                    <div class="text-red-400 text-sm rounded border border-red-400 p-1 mt-1" v-if="form.errors.price">{{ form.errors.price }}</div>

                    <label class="block mt-2 font-medium text-white">Product Images</label>
                    <div
                        ref="dropzoneRef"
                        class="dropzone flex flex-col items-center justify-center border-2 border-dashed border-blue-300 bg-blue-50 hover:bg-blue-100 transition-colors duration-300 rounded-2xl py-10 cursor-pointer"
                    >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-12 h-12 text-blue-400 mb-3"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0115.9 6H16a5 5 0 011 9.9M12 11v6m0 0l-2.5-2.5M12 17l2.5-2.5" />
                        </svg>
                        <p class="text-blue-500 font-medium">Drop files here or click to upload</p>
                        <p class="text-gray-500 text-sm mt-1">PNG, JPG, up to 5MB each</p>
                    </div>

                    <button class="bg-gradient-to-r from-gray-500 to-blue-500 text-white font-bold py-2 px-4 rounded-md mt-4 hover:bg-green-600 hover:to-blue-600 transition ease-in-out duration-150" type="submit" :disabled="form.processing">Update</button>
                    </form>
                </div>
            </div>
    </AppLayout>

</template>
