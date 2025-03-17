import type { TaskStatus, TaskPriority } from '@/constants/task';

export interface Task {
    id: number
    user_id: number
    title: string
    description: string | null
    status: TaskStatus
    priority: TaskPriority
    due_date: string | null
    completed_at: string | null
    created_at: string
    updated_at: string
    user?: User
    categories?: Category[]
    tags?: Tag[]
    subtasks?: Subtask[]
    attachments?: Attachment[]
    reminders?: Reminder[]
    category_ids: number[]
    tag_ids: number[]
}

export interface Category {
    id: number
    user_id: number
    name: string
    color: string // Format: "#RRGGBB"
    created_at: string
    updated_at: string
    user?: User
    tasks?: Task[]
}

export interface Tag {
    id: number
    user_id: number
    name: string
    created_at: string
    updated_at: string
    user?: User
    tasks?: Task[]
}

export interface Subtask {
    id: number
    task_id: number
    title: string
    is_completed: boolean
    created_at: string
    updated_at: string
    task?: Task
}

export interface Attachment {
    id: number
    task_id: number
    filename: string
    path: string
    type: string
    size: number
    created_at: string
    updated_at: string
    task?: Task
}

export interface Reminder {
    id: number
    task_id: number
    reminder_time: string
    is_sent: boolean
    created_at: string
    updated_at: string
    task?: Task
}

export interface CategoryTask {
    task_id: number
    category_id: number
}

export interface TagTask {
    task_id: number
    tag_id: number
}
