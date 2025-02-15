<template>

    <Head title="Dashboard" />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
        <n-card class="rounded-xl" v-for="(summary, i) in summaries" :key="i">
            <n-space align="center">
                <n-icon size="50">
                    <component :is="summary.icon" />
                </n-icon>
                <n-flex vertical size="small">
                    <p class="text-sm font-medium">{{ summary.label }}</p>
                    <p class="text-2xl font-semibold">{{ summary.value }}</p>
                </n-flex>
            </n-space>
        </n-card>
    </div>
</template>

<script setup lang="ts">
import Panel from '@/Layouts/Panel.vue';
import { Head } from '@inertiajs/vue3';
import { Application, Close, InProgress } from '@vicons/carbon';
import { TimerOff24Regular } from '@vicons/fluent';
import { MdCheckmark } from '@vicons/ionicons4';
import { LeaderboardRound, ManageAccountsRound, NotInterestedRound } from '@vicons/material';
import { NIcon } from 'naive-ui';

defineOptions({ layout: Panel })

const summaries = [
    { label: 'Total Lead', value: 0, icon: LeaderboardRound },
    { label: 'Lead In Progress', value: 0, icon: InProgress },
    { label: 'Lead Bad Timing', value: 0, icon: TimerOff24Regular },
    { label: 'Lead Not Interested', value: 0, icon: NotInterestedRound },
    { label: 'Lead Not Qualified', value: 0, icon: Close },

    { label: 'Total Application', value: 0, icon: Application },
    { label: 'Application In Progress', value: 0, icon: InProgress },
    { label: 'Application Approved', value: 0, icon: MdCheckmark },
    { label: 'Application Rejected', value: 0, icon: Close },

    { label: 'Total Counselor', value: 0, icon: ManageAccountsRound },
]
</script>
