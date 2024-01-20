<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {Head, router, useForm, usePage} from '@inertiajs/vue3';
    import { onMounted,ref,computed } from 'vue';
    import DataTable from 'primevue/datatable';
    import Column from 'primevue/column';
    import Button from 'primevue/Button';
    import Tag from 'primevue/Tag';
    import 'primeicons/primeicons.css'
    import 'primevue/resources/themes/lara-light-green/theme.css'
    import ConfirmDialog from 'primevue/confirmdialog';
    import { useConfirm } from "primevue/useconfirm";
    import {useToast} from "vue-toastification";

    const page = usePage();
    const permissions = computed(() => page.props.permissions);
    const statuses = computed(() => page.props.statuses);
    const auth = computed(() => page.props.auth.user);
    const pause = computed(() => statuses.value.find((item) => item.return === true));
    const completed = computed(() =>  page.props.statuses.find((item) => item.last === true));
    const first = computed(() =>  page.props.statuses.find((item) => item.first === true));

    function can(value){
        return permissions.value.some((item) => item.name === value);
    }

    onMounted(() => {
        Echo.private("App.Models.User." + auth.value.id).notification((item) => {
            if(item.code === "UPDATED"){
                let task = item.task;
                setToast('Task : ' + task.title + " Was Updated",false,15000);
            }
            else if(item.code === "CREATED"){
                let task = item.task;
                setToast('Task : ' + task.title + " Was Created",false,15000);
                // Reload the current route
            }
            else if(item.code === "DELETED"){
                let task = item.task;
                setToast('Task : ' + task.title +  " Was deleted",true,15000);
            }
            router.reload();

        }).error((e) => {
            console.log(e.error);
        });
    });


    const confirm = useConfirm();

    defineProps({
        tasks: {
            type: Array
        }
    });

    function setToast(message,error=false,timout = 2000){
        const toast = useToast();

        if(!error) {
            toast.success(message, {
                timeout: timout
            });
        }
        else {
            toast.error(message, {
                timeout: timout
            });
        }
    }

    function deleteRecord(id){
        router.delete(route('tasks.delete',{ 'task': id }), {
            onSuccess: () => {
                setToast('Task has been delete');
                // form.reset('title', 'description','priority','assigned_to','due_date');
            },
            onError : () => setToast('Error',true)
        });
    }

    function findAndReturnNext(value) {
        value = statuses.value.find((item) => item.id === value);
        const index = statuses.value.indexOf(value);
        if (index !== -1 && index < statuses.value.length - 1) {
            return statuses.value[index + 1];
        } else {
            return null;
        }
    }


    function setStatusRecord(id,status){

        let d;
        if(status){
            d = {
                status : status
            }
        }
        else {
            // d = {
            //     status : todo ? 'In Progress' : (inProgress ? 'Completed' : id.status)
            // }

            d = {
                status : id.status === pause.value.id ? findAndReturnNext(pause.value.next).id : findAndReturnNext(id.status).id
            }
        }


        const form = useForm(d);

        form.put(route('tasks.updateStatus',{ 'task': id }), {
            onSuccess: () => {
                setToast('Task has been updated');
                // form.reset('title', 'description','priority','assigned_to','due_date');
            },
            onError : (e) =>{
                setToast('Error',true);
                console.log(e);
            }
        });
    }

    function editTask(task){
        router.visit(route('tasks.edit',{ 'task': task.id }));
    }

    // function canEditTask(task){
    //     return task.user_id === auth.value.id || can('edit task');
    // }
    //
    // function canDeleteTask(task){
    //     return task.user_id === auth.value.id || can('delete task');
    // }

    const showConfirm = (event) => {
        confirm.require({
            message: 'Do you want to delete this record?',
            icon: 'pi pi-info-circle',
            acceptClass: 'p-button-danger p-button-sm',
            accept: () => {
                console.log('accept');
                deleteRecord(event);
            },
            reject: () => {
                console.log('reject');
            }
        });
    };

    const showSetStatusConfirm = (event,status) => {
        confirm.require({
            message: 'Do you want to update this record?',
            icon: 'pi pi-info-circle',
            acceptClass: 'p-button-green p-button-sm',
            accept: () => {
                setStatusRecord(event,status);
            },
            reject: () => {
                console.log('reject');
            }
        });
    };


    function buildClass(data){
        let obj = {};
        statuses.value.map((item) => {
            if((data.status === pause.value.id) && (item.id === pause.value.next))
            {
                obj[item.icon] = true;
            }
            else {
                if (item.icon)
                    obj[item.icon] = ((data.status === item.id) && (data.status !== pause.value.id))  && (data.status !== completed.value.id);
            }
        });
        return obj;
    }

    function buildPauseClass(data){
        let obj = {};
        obj[pause.value.icon] = data.status !== completed.value.id && (data.status !== first.value.id ) && (data.status !== pause.value.id);
        return obj;
    }

    const columns = [
        { data: 'id' },
        { data: 'title' },
        { data: 'user_assigned_to' },
        { data: 'description' },
        { data: 'status' },
        { data: 'due_date' },
    ];

    const getSeverity = (task) => {
        return statuses.value.find((item) => item.id === task.status).color;
    }

</script>

<template>


    <div class="m-5">

        <DataTable   :value="tasks" dataKey="id"
                   :paginator="true" :rows="10"
                   paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
                   currentPageReportTemplate="Showing {first} to {last} of {totalRecords} tasks">
            <Column field="id" header="Id"></Column>
            <Column field="title" header="Title"></Column>
            <Column field="user_assigned_to" header="Assigned To"></Column>
            <Column field="description" header="Description"></Column>
            <Column header="Status">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data)" />
                </template>
            </Column>
            <Column field="due_date" header="Due Date"></Column>
            <Column :exportable="false" style="min-width:8rem">
                <template #body="slotProps">
                    <Button v-if="can('edit task')" icon="pi pi-pencil" outlined rounded class="mr-2" @click="editTask(slotProps.data)" />
                    <Button  v-if="can('delete task')" icon="pi pi-trash" outlined rounded severity="danger" @click="showConfirm(slotProps.data)" />
                </template>
            </Column>
            <Column  header="Actions" :exportable="false" style="min-width:8rem">
                <template #body="{ data }">
                    <div>
                        <i @click="showSetStatusConfirm(data,pause['id'])" class="pi " :class="buildPauseClass(data)"></i>
                    </div>
                    <i @click="showSetStatusConfirm(data)" class="pi " :class="buildClass(data)"></i>
                </template>

            </Column>
        </DataTable>
        <ConfirmDialog></ConfirmDialog>
    </div>

</template>
