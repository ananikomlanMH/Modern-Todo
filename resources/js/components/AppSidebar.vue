<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarGroup } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    Folder,
    LayoutGrid,
    Calendar,
    BarChart,
    Users,
    Settings,
    Tags,
    ListTodo
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import NavMain from '@/components/NavMain.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Tâches',
        href: '/tasks',
        icon: ListTodo,
    },
    {
        title: 'Projets',
        href: '/projects',
        icon: Folder,
    },
    {
        title: 'Calendrier',
        href: '/calendar',
        icon: Calendar,
    },
    {
        title: 'Rapports',
        href: '/reports',
        icon: BarChart,
    },
    {
        title: 'Équipe',
        href: '/team',
        icon: Users,
    },
    {
        title: 'Paramètres',
        icon: Settings,
        items: [
            {
                title: 'Catégories',
                href: '/tasks/categories',
                icon: Folder,
            },
            {
                title: 'Tags',
                href: '/tasks/tags',
                icon: Tags,
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    {
        title: 'Documentation',
        href: 'https://github.com/ananikomlanMH/Modern-Todo',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
