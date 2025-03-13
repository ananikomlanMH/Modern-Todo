<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger, SheetDescription } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { DateFormatter, type DateValue, getLocalTimeZone, parseDate, today } from '@internationalized/date';
import { cn } from '@/utils';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import TaskSheet from '@/components/tasks/TaskSheet.vue';
import TaskCard from '@/components/tasks/TaskCard.vue';
import TaskTable from '@/components/tasks/TaskTable.vue';

const props = defineProps<{
  tasks: any[];
}>();

const viewType = ref<'card' | 'table'>('card');

const df = new DateFormatter('fr-FR', {
  dateStyle: 'long',
});

const form = useForm({
  title: '',
  description: '',
  status: 'pending',
  priority: 'medium',
  due_date: null as DateValue | null,
  category_ids: [],
  tag_ids: [],
});

const statuses = ['pending', 'in_progress', 'completed'];
const priorities = ['low', 'medium', 'high'];

const createTask = () => {
  if (isEditing.value && currentTask.value) {
    form.put(route('tasks.update', currentTask.value.id), {
      onSuccess: () => {
        form.reset();
        isEditing.value = false;
        currentTask.value = null;
        isSheetOpen.value = false;
      },
    });
  } else {
    form.post(route('tasks.store'), {
      onSuccess: () => {
        form.reset();
        isSheetOpen.value = false;
      },
    });
  }
};

const parseDateSafely = (dateString: string | null): DateValue | null => {
  if (!dateString) return null;
  try {
    const datePart = dateString.split('T')[0];
    return parseDate(datePart);
  } catch (e) {
    console.error('Error parsing date:', e);
    return null;
  }
};

const editTask = (task: any) => {
  form.title = task.title;
  form.description = task.description;
  form.status = task.status;
  form.priority = task.priority;
  form.due_date = parseDateSafely(task.due_date);
  form.category_ids = task.category_ids;
  form.tag_ids = task.tag_ids;
  
  isEditing.value = true;
  currentTask.value = task;
  isSheetOpen.value = true;
};

const deleteTask = (task: any) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
    router.delete(route('tasks.destroy', task.id));
  }
};

const isEditing = ref(false);
const currentTask = ref<any>(null);
const isSheetOpen = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tâches',
        href: '/tasks',
    },
];

const handleSubmit = () => {
  if (isEditing.value && currentTask.value) {
    form.put(route('tasks.update', currentTask.value.id), {
      onSuccess: () => {
        form.reset();
        isEditing.value = false;
        currentTask.value = null;
        isSheetOpen.value = false;
      },
    });
  } else {
    form.post(route('tasks.store'), {
      onSuccess: () => {
        form.reset();
        isSheetOpen.value = false;
      },
    });
  }
};
</script>

<template>
    <Head title="Tâches" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

        <div class="flex justify-between items-center mb-6">
            <div class="flex gap-2">
                <Button
                    :variant="viewType === 'card' ? 'default' : 'outline'"
                    @click="viewType = 'card'"
                >
                    Cards
                </Button>
                <Button
                    :variant="viewType === 'table' ? 'default' : 'outline'"
                    @click="viewType = 'table'"
                >
                    Table
                </Button>
            </div>

            <TaskSheet
                v-model:open="isSheetOpen"
                :form="form"
                :is-editing="isEditing"
                :statuses="statuses"
                :priorities="priorities"
                @submit="handleSubmit"
            />
        </div>

        <!-- Card View -->
        <div v-if="viewType === 'card'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <TaskCard
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @click="editTask(task)"
            />
        </div>

        <!-- Table View -->
        <TaskTable
            v-else
            :tasks="tasks"
            @edit="editTask"
            @delete="deleteTask"
        />

        </div>
    </AppLayout>
</template>
