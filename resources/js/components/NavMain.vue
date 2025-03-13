<script setup lang="ts">
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub, SidebarMenuSubItem
} from '@/components/ui/sidebar';

import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown } from 'lucide-vue-next';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <div class="px-2 py-0">
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <!-- Regular items -->
                <SidebarMenuItem v-if="!item.items" class="mb-1">
                    <SidebarMenuButton as-child :is-active="item.href === page.url">
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>

                <SidebarMenu v-else>
                    <Collapsible :default-open="item.items.some(subItem => subItem.href === page.url)" class="group/collapsible">
                        <SidebarMenuItem class="mb-1">
                            <CollapsibleTrigger asChild>
                                <SidebarMenuButton>
                                    <component :is="item.icon" />
                                    <a href="#">{{ item.title }}</a>
                                    <ChevronDown class="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-180" />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>
                            <CollapsibleContent>
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem
                                        v-for="subItem in item.items"
                                        :key="subItem.title">
                                        <SidebarMenuButton as-child :is-active="subItem.href === page.url">
                                            <Link :href="subItem.href">
                                                <span>{{ subItem.title }}</span>
                                            </Link>
                                        </SidebarMenuButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>
                </SidebarMenu>
            </template>
        </SidebarMenu>
    </div>
</template>
