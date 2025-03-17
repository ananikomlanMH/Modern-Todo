<script setup lang="ts">
import { taskStatuses, taskPriorities } from '@/constants/task';
import type { Task } from '@/types/model';

defineProps<{
    task: Task;
}>();
</script>

<template>
    <div 
        class="task-card p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer border border-gray-200"
        :class="taskStatuses[task.status].class"
    >
        <!-- En-tête de la carte -->
        <div class="flex justify-between items-start mb-3">
            <h3 class="text-lg font-semibold">{{ task.title }}</h3>
            
            <!-- Badges de statut et priorité -->
            <div class="flex flex-col gap-2 items-end">
                <!-- Badge de statut -->
                <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="taskStatuses[task.status].class"
                >
                    {{ taskStatuses[task.status].icon }}
                    {{ taskStatuses[task.status].label }}
                </span>
                
                <!-- Badge de priorité -->
                <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="taskPriorities[task.priority].class"
                >
                    {{ taskPriorities[task.priority].icon }}
                    {{ taskPriorities[task.priority].label }}
                </span>
            </div>
        </div>

        <!-- Description de la tâche -->
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
            {{ task.description }}
        </p>

        <!-- Pied de la carte -->
        <div class="flex justify-between items-center text-sm text-gray-500">
            <!-- Date d'échéance si elle existe -->
            <span v-if="task.due_date" class="flex items-center">
                📅 {{ new Date(task.due_date).toLocaleDateString() }}
            </span>
            
            <!-- Catégories/Tags si nécessaire -->
            <div class="flex gap-2">
                <span v-for="category in task.category_ids" :key="category" 
                    class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                    {{ category }}
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.task-card {
    background-color: white;
    transition: all 0.2s ease;
}

.task-card:hover {
    transform: translateY(-2px);
}

/* Classes pour les badges de statut */
.status-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}

/* Classes pour les badges de priorité */
.priority-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}
</style> 