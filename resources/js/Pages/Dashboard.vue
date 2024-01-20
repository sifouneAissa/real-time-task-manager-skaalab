<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskDatatable from '@/Pages/Tasks/TaskDatatable.vue';
import { Head,usePage } from '@inertiajs/vue3';
import { computed } from 'vue'

defineProps({
    tasks: {
        type: Array
    },
});



</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in ! {{$page.props.auth.user.name}}</div>
                </div>

            </div>
        </div>

        <TaskDatatable :tasks="tasks"  />

    </AuthenticatedLayout>

</template>
