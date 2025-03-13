<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { MoreVertical, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
  tasks: any[];
}>();

const emit = defineEmits<{
  'edit': [task: any];
  'delete': [task: any];
}>();
</script>

<template>
  <Table>
    <TableHeader>
      <TableRow>
        <TableHead>Titre</TableHead>
        <TableHead>Status</TableHead>
        <TableHead>Priorité</TableHead>
        <TableHead>Date d'échéance</TableHead>
        <TableHead class="w-[50px]">Actions</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="task in tasks" :key="task.id" class="group">
        <TableCell>{{ task.title }}</TableCell>
        <TableCell>
          <Badge :variant="task.status === 'completed' ? 'success' : 'default'">
            {{ task.status }}
          </Badge>
        </TableCell>
        <TableCell>
          <Badge :variant="task.priority === 'high' ? 'destructive' : 'secondary'">
            {{ task.priority }}
          </Badge>
        </TableCell>
        <TableCell>
          <Badge variant="outline" v-if="task.due_date">
            {{ new Date(task.due_date).toLocaleDateString('fr-FR') }}
          </Badge>
          <span v-else>-</span>
        </TableCell>
        <TableCell>
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="icon">
                <MoreVertical class="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-[160px]">
              <DropdownMenuItem @click="emit('edit', task)">
                <Pencil class="mr-2 h-4 w-4" />
                Modifier
              </DropdownMenuItem>
              <DropdownMenuItem @click="emit('delete', task)" class="text-destructive">
                <Trash2 class="mr-2 h-4 w-4" />
                Supprimer
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template> 