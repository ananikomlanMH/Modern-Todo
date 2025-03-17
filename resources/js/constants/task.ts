export const taskStatuses = {
    pending: { icon: '🕒', label: 'Pending', class: 'bg-gray-100' },
    in_progress: { icon: '▶️', label: 'In Progress', class: 'bg-blue-100' },
    completed: { icon: '✅', label: 'Completed', class: 'bg-green-100' },
    cancelled: { icon: '❌', label: 'Cancelled', class: 'bg-red-100' }
} as const;

export const taskPriorities = {
    low: { icon: '🟢', label: 'Low', class: 'bg-green-100 text-green-800' },
    medium: { icon: '🟡', label: 'Medium', class: 'bg-yellow-100 text-yellow-800' },
    high: { icon: '🟠', label: 'High', class: 'bg-orange-100 text-orange-800' },
    urgent: { icon: '🔴', label: 'Urgent', class: 'bg-red-100 text-red-800' }
} as const;

export type TaskStatus = keyof typeof taskStatuses;
export type TaskPriority = keyof typeof taskPriorities; 