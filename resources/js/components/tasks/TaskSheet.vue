<script setup lang="ts">
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger, SheetDescription } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { DateFormatter, getLocalTimeZone } from '@internationalized/date';
import { cn } from '@/utils';

defineProps<{
  form: any;
  isEditing: boolean;
  open: boolean;
  statuses: string[];
  priorities: string[];
}>();

const emit = defineEmits<{
  'update:open': [value: boolean];
  'submit': [];
  'newTask': [];
}>();

const df = new DateFormatter('fr-FR', { dateStyle: 'long' });

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

</script>

<template>
  <Sheet :open="open" @update:open="(val) => emit('update:open', val)">
    <SheetTrigger as-child>
      <Button @click="emit('newTask')">New Task</Button>
    </SheetTrigger>
    <SheetContent>
      <SheetHeader>
        <SheetTitle>{{ isEditing ? 'Edit Task' : 'Create New Task' }}</SheetTitle>
        <SheetDescription>
          {{ isEditing ? 'Edit the details of the task' : 'Add a new task to your list' }}
        </SheetDescription>
      </SheetHeader>
      <form @submit.prevent="emit('submit')" class="space-y-4 mt-4">
        <div>
          <Input
            v-model="form.title"
            placeholder="Title"
            :class="{ 'border-red-500': form.errors.title }"
          />
          <p v-if="form.errors.title" class="text-sm text-red-500 mt-1">
            {{ form.errors.title }}
          </p>
        </div>
        <div>
          <Textarea v-model="form.description" placeholder="Description" />
        </div>
        <div>
          <Select v-model="form.status">
            <SelectTrigger>
              <SelectValue placeholder="Status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="status in statuses" :key="status" :value="status">
                {{ taskStatuses[status].icon }} {{ taskStatuses[status].label }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
        <div>
          <Select v-model="form.priority">
            <SelectTrigger>
              <SelectValue placeholder="Priority" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="priority in priorities" :key="priority" :value="priority">
                {{ taskPriorities[priority].icon }} {{ taskPriorities[priority].label }}
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
                  form.errors.due_date && 'border-red-500'
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
          <p v-if="form.errors.due_date" class="text-sm text-red-500 mt-1">
            {{ form.errors.due_date }}
          </p>
        </div>
        <Button type="submit" :disabled="form.processing">
          {{ isEditing ? 'Edit' : 'Create' }}
        </Button>
      </form>
    </SheetContent>
  </Sheet>
</template>
