<template>
    <n-layout class="h-screen">
        <n-layout-header bordered class="p-1 h-[60px]">
            <n-space align="center" justify="space-between">
                <Link href="/dashboard">
                    <n-space align="center">
                        <n-image :src="Images.logo" preview-disabled width="50"/>
                        <p class="text-xl font-semibold">SimpleCRM</p>
                    </n-space>
                </Link>

                <n-dropdown :on-select="userOptionHandler" :options="userOptions">
                    <n-button quaternary size="large">
                        <template #icon>
                            <n-image :src="Images.logo"/>
                        </template>
                        {{ currentUser?.name ?? 'Anonymous' }}
                    </n-button>
                </n-dropdown>
            </n-space>
        </n-layout-header>
        <n-layout class="!top-[60px]" has-sider position="absolute">
            <n-layout-sider :collapsed-width="64" :native-scrollbar="false" :width="200" bordered collapse-mode="width"
                            show-trigger>
                <n-menu :collapsed-icon-size="22" :collapsed-width="64" :on-update:value="pageRouter"
                        :options="navOptions"/>
            </n-layout-sider>
            <n-layout-content class="p-2">
                <slot/>
            </n-layout-content>
        </n-layout>
    </n-layout>
    <VueQueryDevtools/>
</template>

<script lang="ts" setup>
import {Images} from '@/Constants/theme';
import {Link, router, usePage} from '@inertiajs/vue3';
import {Dashboard, TaskApproved, UserAvatar} from '@vicons/carbon';
import {IosLogOut} from '@vicons/ionicons4';
import {Stack2} from '@vicons/tabler';
import {NIcon, useMessage} from 'naive-ui';
import {Component, h, inject} from 'vue';
import {VueQueryDevtools} from '@tanstack/vue-query-devtools'
import {useMutation} from '@tanstack/vue-query';
import {logout} from '@/States/Actions/Auth';
import Cookies from 'js-cookie';

const currentUser = inject('currentUser');
const message = useMessage()

const logoutMutation = useMutation({
    mutationFn: () => logout(),
    onSuccess: (data) => {
        Cookies.remove('token')
        router.visit('/login')
    },
    onError: (error) => {
        message.error(error.message)
    }
})

const renderIcon = (icon: Component) => {
    return () => h(NIcon, null, {default: () => h(icon)})
}

const pageRouter = (key: string) => {
    router.visit(key)
}

const userOptionHandler = (key: string) => {
    if (key === 'logout') {
        logoutMutation.mutate()
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
