<script setup lang="ts">
import TaskCard from '@/components/tasks/TaskCard.vue';
import TaskSheet from '@/components/tasks/TaskSheet.vue';
import TaskTable from '@/components/tasks/TaskTable.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { TaskService } from '@/services/taskService';
import { type BreadcrumbItem } from '@/types';
import { type Task } from '@/types/model';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    tasks: Task[];
}>();

const viewType = ref<'card' | 'table'>('card');

const form = useForm({
    title: '',
    description: '',
    status: 'pending',
    priority: 'medium',
    due_date: null,
    category_ids: [],
    tag_ids: [],
});

// Définition des statuts avec leurs icônes et labels
const taskStatuses = {
    pending: { icon: '🕒', label: 'Pending', class: 'bg-gray-100' },
    in_progress: { icon: '▶️', label: 'In Progress', class: 'bg-blue-100' },
    completed: { icon: '✅', label: 'Completed', class: 'bg-green-100' },
    cancelled: { icon: '❌', label: 'Cancelled', class: 'bg-red-100' }
};

// Définition des priorités avec leurs couleurs et labels
const taskPriorities = {
    low: { icon: '🟢', label: 'Low', class: 'text-green-500' },
    medium: { icon: '🟡', label: 'Medium', class: 'text-yellow-500' },
    high: { icon: '🟠', label: 'High', class: 'text-orange-500' },
    urgent: { icon: '🔴', label: 'Urgent', class: 'text-red-500' }
};

// Ajout aux données existantes
const statuses = Object.keys(taskStatuses);
const priorities = Object.keys(taskPriorities);

const isEditing = ref(false);
const currentTask = ref<Task | null>(null);
const isSheetOpen = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tâches',
        href: '/tasks',
    },
];

const handleSubmit = async () => {
    const isValid = TaskService.validateForm(form);

    if (!isValid) {
        isSheetOpen.value = true;
        return;
    }

    if (isEditing.value && currentTask.value) {
        await TaskService.updateTask(currentTask.value.id, form);
        form.reset();
        isEditing.value = false;
        currentTask.value = null;
        isSheetOpen.value = false;
    } else {
        await TaskService.createTask(form);
        form.reset();
        isSheetOpen.value = false;
    }
};

const editTask = (task: Task) => {
    form.title = task.title;
    form.description = task.description;
    form.status = task.status;
    form.priority = task.priority;
    form.due_date = TaskService.parseDateSafely(task.due_date);
    form.category_ids = task.category_ids;
    form.tag_ids = task.tag_ids;

    isEditing.value = true;
    currentTask.value = task;
    isSheetOpen.value = true;
};

const deleteTask = (task: Task) => {
    TaskService.deleteTask(task.id);
};

const resetTaskForm = () => {
    form.reset();
    isEditing.value = false;
    currentTask.value = null;
    isSheetOpen.value = true;
};

</script>

<template>
    <Head title="Tâches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex gap-2">
                    <Button :variant="viewType === 'card' ? 'default' : 'outline'" @click="viewType = 'card'"> Cards </Button>
                    <Button :variant="viewType === 'table' ? 'default' : 'outline'" @click="viewType = 'table'"> Table </Button>
                </div>

                <TaskSheet
                    v-model:open="isSheetOpen"
                    :form="form"
                    :is-editing="isEditing"
                    :statuses="statuses"
                    :priorities="priorities"
                    @submit="handleSubmit"
                    @new-task="resetTaskForm"
                />
            </div>

            <!-- Card View -->
            <div v-if="viewType === 'card'" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <TaskCard v-for="task in tasks" :key="task.id" :task="task" @click="editTask(task)" />
            </div>

            <!-- Table View -->
            <TaskTable v-else :tasks="tasks" @edit="editTask" @delete="deleteTask" />
        </div>
    </AppLayout>
</template>
