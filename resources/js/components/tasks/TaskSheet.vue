<script setup lang="ts">
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger, SheetDescription } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { DateFormatter, type DateValue, getLocalTimeZone } from '@internationalized/date';
import { cn } from '@/utils';

const props = defineProps<{
  form: any;
  isEditing: boolean;
  open: boolean;
  statuses: string[];
  priorities: string[];
}>();

const emit = defineEmits<{
  'update:open': [value: boolean];
  'submit': [];
}>();

const df = new DateFormatter('fr-FR', { dateStyle: 'long' });
</script>

<template>
  <Sheet :open="open" @update:open="(val) => emit('update:open', val)">
    <SheetTrigger as-child>
      <Button>Nouvelle tâche</Button>
    </SheetTrigger>
    <SheetContent>
      <SheetHeader>
        <SheetTitle>{{ isEditing ? 'Modifier la tâche' : 'Créer une nouvelle tâche' }}</SheetTitle>
        <SheetDescription>
          {{ isEditing ? 'Modifier les détails de la tâche' : 'Ajouter une nouvelle tâche à votre liste' }}
        </SheetDescription>
      </SheetHeader>
      <form @submit.prevent="emit('submit')" class="space-y-4 mt-4">
        <div>
          <Input 
            v-model="form.title" 
            placeholder="Titre"
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
          {{ isEditing ? 'Modifier' : 'Créer' }}
        </Button>
      </form>
    </SheetContent>
  </Sheet>
</template> 