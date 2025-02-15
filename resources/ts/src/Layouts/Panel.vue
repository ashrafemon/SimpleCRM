<template>
    <n-layout class="h-screen">
        <n-layout-header bordered class="p-1 h-[60px]">
            <n-space justify="space-between" align="center">
                <Link href="/dashboard">
                <n-space align="center">
                    <n-image :src="Images.logo" width="50" preview-disabled />
                    <p class="text-xl font-semibold">SimpleCRM</p>
                </n-space>
                </Link>

                <n-dropdown :options="userOptions" :on-select="userOptionHandler">
                    <n-button size="large" quaternary>
                        <template #icon>
                            <n-image :src="Images.logo" />
                        </template>
                        Admin User
                    </n-button>
                </n-dropdown>
            </n-space>
        </n-layout-header>
        <n-layout has-sider position="absolute" class="!top-[60px]">
            <n-layout-sider bordered show-trigger collapse-mode="width" :collapsed-width="64" :width="200"
                :native-scrollbar="false">
                <n-menu :collapsed-width="64" :collapsed-icon-size="22" :options="navOptions"
                    :on-update:value="pageRouter" />
            </n-layout-sider>
            <n-layout-content class="p-2">
                <slot />
            </n-layout-content>
        </n-layout>
    </n-layout>
</template>

<script setup lang="ts">
import { Images } from '@/Constants/theme';
import { Link, router } from '@inertiajs/vue3';
import { Dashboard, TaskApproved, UserAvatar } from '@vicons/carbon';
import { IosLogOut } from '@vicons/ionicons4';
import { Stack2 } from '@vicons/tabler';
import { NIcon } from 'naive-ui';
import { Component, h } from 'vue';

const renderIcon = (icon: Component) => {
    return () => h(NIcon, null, { default: () => h(icon) })
}

const pageRouter = (key: string) => {
    router.visit(key)
}

const userOptionHandler = (key: string) => {
    if (key === 'logout') {
        router.visit('/login')
    }
}

const navOptions = [
    {
        label: 'Dashboard',
        key: 'dashboard',
        icon: renderIcon(Dashboard)
    },
    {
        label: 'Leads',
        key: 'leads',
        icon: renderIcon(Stack2)
    },
    {
        label: 'Applications',
        key: 'applications',
        icon: renderIcon(TaskApproved)
    },
]

const userOptions = [
    {
        label: 'Profile',
        key: 'profile',
        icon: renderIcon(UserAvatar)
    },
    {
        type: 'divider'
    },
    {
        label: 'Logout',
        key: 'logout',
        icon: renderIcon(IosLogOut),
    },
]
</script>
