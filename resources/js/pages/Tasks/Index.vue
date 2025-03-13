<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { DateFormatter, type DateValue, getLocalTimeZone } from '@internationalized/date';
import { cn } from '@/utils';
import { type BreadcrumbItem } from '@/types';

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
  form.post(route('tasks.store'), {
    onSuccess: () => {
      form.reset();
    },
  });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tâches',
        href: '/tasks',
    },
];
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

            <Sheet>
                <SheetTrigger as-child>
                    <Button>Nouvelle tâche</Button>
                </SheetTrigger>
                <SheetContent>
                    <SheetHeader>
                        <SheetTitle>Créer une nouvelle tâche</SheetTitle>
                    </SheetHeader>
                    <form @submit.prevent="createTask" class="space-y-4 mt-4">
                        <div>
                            <Input v-model="form.title" placeholder="Titre" />
                        </div>
                        <div>
                            <Textarea v-model="form.description" placeholder="Description" />
                        </div>
                        <div>
                            <Select v-model="form.status">
                                <SelectTrigger>
                                    <SelectValue placeholder="Statut" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="status in statuses" :key="status" :value="status">
                                        {{ status }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Select v-model="form.priority">
                                <SelectTrigger>
                                    <SelectValue placeholder="Priorité" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="priority in priorities" :key="priority" :value="priority">
                                        {{ priority }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Popover>
                                <PopoverTrigger as-child>
                                    <Button
                                        variant="outline"
                                        :class="cn(
                                            'w-full justify-start text-left font-normal',
                                            !form.due_date && 'text-muted-foreground',
                                        )"
                                    >
                                        <CalendarIcon class="mr-2 h-4 w-4" />
                                        {{ form.due_date
                                            ? df.format(form.due_date.toDate(getLocalTimeZone()))
                                            : "Choisir une date" }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto p-0">
                                    <Calendar v-model="form.due_date" initial-focus />
                                </PopoverContent>
                            </Popover>
                        </div>
                        <Button type="submit" :disabled="form.processing">Créer</Button>
                    </form>
                </SheetContent>
            </Sheet>
        </div>

        <!-- Card View -->
        <div v-if="viewType === 'card'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Card v-for="task in tasks" :key="task.id">
                <CardHeader>
                    <CardTitle>{{ task.title }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <p>{{ task.description }}</p>
                    <div class="mt-2">
                        <span class="text-sm">Status: {{ task.status }}</span>
                        <span class="text-sm ml-2">Priority: {{ task.priority }}</span>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Table View -->
        <div v-else>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Titre</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Priorité</TableHead>
                        <TableHead>Date d'échéance</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="task in tasks" :key="task.id">
                        <TableCell>{{ task.title }}</TableCell>
                        <TableCell>{{ task.status }}</TableCell>
                        <TableCell>{{ task.priority }}</TableCell>
                        <TableCell>{{ task.due_date }}</TableCell>
                        <TableCell>
                            <Button variant="ghost" size="sm" @click="editTask(task)">Éditer</Button>
                            <Button variant="destructive" size="sm" @click="deleteTask(task)">Supprimer</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        </div>
    </AppLayout>
</template>
