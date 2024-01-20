<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import Multiselect from '@suadelabs/vue3-multiselect'
    import { useToast } from "vue-toastification";
    import "@suadelabs/vue3-multiselect/dist/vue3-multiselect.css";
    import { Head, Link, useForm } from '@inertiajs/vue3';

    defineProps({
        users: {
            type: Array
        }
    });

    const form = useForm({
        title: '',
        description: '',
        priority: '',
        assigned_to: [],
        due_date : '',
    });

    const today = new Date();
    const format = (date) => {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        return `Due Date is : ${day}/${month}/${year}`;
    }

    function setToast(message,error=false){
        const toast = useToast();

        if(!error)
        toast.success(message, {
            timeout: 2000
        });
        else
        toast.error(message, {
            timeout: 2000
        });
    }

    const submit = () => {
        form.post(route('tasks.store'), {
            onSuccess: () => {
                setToast('Task has been created');
                form.reset('title', 'description','priority','assigned_to','due_date');
            },
            onError : () => setToast('Error',true)
        });
    };

    const prps = [
        "High",
        "Medium",
        "Low"
    ];


    const  disabledDates =  {
        to: new Date(Date.now() - 8640000)
    }

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create a Task" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create a task</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="title" value="Title" />

                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Description" />

                            <textarea
                                id="description"
                                type="text"
                                class="mt-1 block w-full rounded-md shadow-sm"
                                v-model="form.description"
                                required
                                autocomplete="description"
                            ></textarea>

                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="priority" value="Priority" />

                            <multiselect
                                v-model="form.priority"
                                :options="prps"
                                :multiple="false"
                                class="mt-1 block w-full"
                                :close-on-select="true"
                                placeholder="Select a priority"
                            >
                            </multiselect>


                            <InputError class="mt-2" :message="form.errors.priority" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="assigned_to" value="Assigned to (If you let it empty it will be assigned for you)" />

                            <multiselect
                                v-model="form.assigned_to"
                                :options="users"
                                :multiple="true"
                                class="mt-1 block w-full"
                                :close-on-select="true"
                                track-by="id"
                                label="name"
                                placeholder="Select a user"
                            >
                            </multiselect>

                            <InputError class="mt-2" :message="form.errors.assigned_to" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="due_date" value="Due Date" />

                            <VueDatePicker v-model="form.due_date" placeholder="Select Date" :min-date="today" :format="format" />

                            <InputError class="mt-2" :message="form.errors.due_date" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Submit
                            </PrimaryButton>
                        </div>
                    </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
