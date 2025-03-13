<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

defineProps<{
  task: any;
}>();
</script>

<template>
  <Card class="group transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
    <CardHeader>
      <CardTitle>{{ task.title }}</CardTitle>
    </CardHeader>
    <CardContent>
      <p class="mb-4">{{ task.description }}</p>
      <div class="flex gap-2 flex-wrap">
        <Badge :variant="task.status === 'completed' ? 'success' : 'default'">
          {{ task.status }}
        </Badge>
        <Badge :variant="task.priority === 'high' ? 'destructive' : 'secondary'">
          {{ task.priority }}
        </Badge>
        <Badge variant="outline" v-if="task.due_date">
          {{ new Date(task.due_date).toLocaleDateString('fr-FR') }}
        </Badge>
      </div>
    </CardContent>
  </Card>
</template> 