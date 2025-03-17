<script setup lang="ts">
import TaskCard from '@/components/tasks/TaskCard.vue';
import TaskSheet from '@/components/tasks/TaskSheet.vue';
import TaskTable from '@/components/tasks/TaskTable.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { TaskService } from '@/services/taskService';
import { type BreadcrumbItem } from '@/types';
import { type Task } from '@/types/model';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { taskStatuses, taskPriorities } from '@/constants/task';
import {
    LayoutGrid,
    Table2,
    Search,
    Plus,
    ChevronDown
} from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';

// Définir props correctement
const props = defineProps<{
    tasks: Task[];
}>();

// View toggle
const viewType = ref<'card' | 'table'>('card');
const toggleView = () => {
    viewType.value = viewType.value === 'card' ? 'table' : 'card';
};

// Filtres
const searchQuery = ref('');
const selectedStatus = ref('all');
const selectedPriority = ref('all');

// Liste filtrée des tâches
const filteredTasks = computed(() => {
    return props.tasks.filter(task => {
        const matchesSearch = task.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            task.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = selectedStatus.value === 'all' || task.status === selectedStatus.value;
        const matchesPriority = selectedPriority.value === 'all' || task.priority === selectedPriority.value;

        return matchesSearch && matchesStatus && matchesPriority;
    });
});

const statuses = Object.keys(taskStatuses);
const priorities = Object.keys(taskPriorities);

const form = useForm({
    title: '',
    description: '',
    status: 'pending',
    priority: 'medium',
    due_date: null,
    category_ids: [],
    tag_ids: [],
});

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
            <!-- Barre d'outils supérieure -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <!-- Filtres -->
                <div class="flex flex-1 flex-wrap gap-3">
                    <!-- Recherche -->
                    <div class="relative flex-1 md:flex-initial md:w-64">
                        <Input
                            v-model="searchQuery"
                            placeholder="Search task..."
                            class="pl-9"
                        />
                        <Search
                            class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
                        />
                    </div>

                    <Popover>
                        <PopoverTrigger as-child>
                            <Button variant="outline" class="w-full md:w-40 justify-between">
                                <div class="flex items-center">
                                    <span v-if="selectedStatus === 'all'">All status</span>
                                    <template v-else>
                                        <span v-html="taskStatuses[selectedStatus].icon" class="mr-2"></span>
                                        <span>{{ taskStatuses[selectedStatus].label }}</span>
                                    </template>
                                </div>
                                <ChevronDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-full p-0">
                            <Command>
                                <CommandInput placeholder="Search status..." />
                                <CommandList>
                                    <CommandEmpty>No status found.</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            @select="() => selectedStatus = 'all'"
                                            :class="{ 'bg-accent': selectedStatus === 'all' }"
                                         value="">
                                            <span>All status</span>
                                        </CommandItem>
                                        <CommandItem
                                            v-for="(status, key) in taskStatuses"
                                            :key="key"
                                            @select="() => selectedStatus = key"
                                            :class="{ 'bg-accent': selectedStatus === key }"
                                         value="">
                                            <span v-html="status.icon" class="mr-2"></span>
                                            <span>{{ status.label }}</span>
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>

                    <!-- Priority Filter -->
                    <Popover>
                        <PopoverTrigger as-child>
                            <Button variant="outline" class="w-full md:w-44 justify-between">
                                <div class="flex items-center">
                                    <span v-if="selectedPriority === 'all'">All priorities</span>
                                    <template v-else>
                                        <span v-html="taskPriorities[selectedPriority].icon" class="mr-2"></span>
                                        <span>{{ taskPriorities[selectedPriority].label }}</span>
                                    </template>
                                </div>
                                <ChevronDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-full p-0">
                            <Command>
                                <CommandInput placeholder="Search priority..." />
                                <CommandList>
                                    <CommandEmpty>No priority found.</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            @select="() => selectedPriority = 'all'"
                                            :class="{ 'bg-accent': selectedPriority === 'all' }"
                                         value="">
                                            <span>Toutes les priorités</span>
                                        </CommandItem>
                                        <CommandItem
                                            v-for="(priority, key) in taskPriorities"
                                            :key="key"
                                            @select="() => selectedPriority = key"
                                            :class="{ 'bg-accent': selectedPriority === key }"
                                         value="">
                                            <span v-html="priority.icon" class="mr-2"></span>
                                            <span>{{ priority.label }}</span>
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <!-- Toggle View Button -->
                    <Button
                        variant="outline"
                        size="icon"
                        @click="toggleView"
                        :title="viewType === 'card' ? 'View as table' : 'View as cards'"
                    >
                        <Table2 v-if="viewType === 'card'" class="h-4 w-4" />
                        <LayoutGrid v-else class="h-4 w-4" />
                    </Button>

                    <!-- Nouveau Button -->
                    <Button @click="resetTaskForm">
                        <Plus class="h-4 w-4 mr-2" />
                        New Task
                    </Button>

                    <!-- Task Sheet -->
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
            </div>

            <br />
            <!-- Message si aucun résultat -->
            <div v-if="filteredTasks.length === 0"
                class="flex flex-col items-center justify-center py-8 text-muted-foreground"
            >
                <Search class="h-12 w-12 mb-4 text-muted-foreground/50" />
                <p>Aucune tâche ne correspond à vos critères de recherche</p>
            </div>

            <!-- Vue Cards avec correction du TransitionGroup -->
                <div v-if="viewType === 'card'" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <TaskCard
                        v-for="task in filteredTasks"
                        :key="task.id"
                        :task="task"
                        @click="editTask(task)"
                    />
                </div>

            <!-- Vue Table -->
            <TaskTable
                v-else
                :tasks="filteredTasks"
                @edit="editTask"
                @delete="deleteTask"
            />
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

.fade-move {
    transition: transform 0.3s ease;
}
</style>
