<template>

    <Head title="Dashboard" />

    <template v-if="isFetching">
        <n-skeleton text :repeat="12" class="w-full" />
    </template>

    <template v-else-if="isError">
        <n-empty description="Failed to fetch data" />
    </template>

    <template v-else>
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
</template>

<script setup lang="ts">
import Panel from '@/Layouts/Panel.vue';
import { Head } from '@inertiajs/vue3';
import { Application, Close, InProgress } from '@vicons/carbon';
import { TimerOff24Regular } from '@vicons/fluent';
import { MdCheckmark } from '@vicons/ionicons4';
import { LeaderboardRound, ManageAccountsRound, NotInterestedRound } from '@vicons/material';
import { NIcon } from 'naive-ui';
import { fetchSummaryReports } from '@/States/Actions/Reports';
import { useQuery } from '@tanstack/vue-query';
import { watch, computed } from 'vue';
defineOptions({ layout: Panel })

const { data, isFetching, isError, error } = useQuery({
    queryKey: ['summary-reports'],
    queryFn: () => fetchSummaryReports(),
})

const summaries = computed(() => {
    return [
        { label: 'Total Lead', value: data.value?.leads ?? 0, icon: LeaderboardRound },
        { label: 'Lead In Progress', value: data.value?.progress_leads ?? 0, icon: InProgress },
        { label: 'Lead Bad Timing', value: data.value?.bad_timing_leads ?? 0, icon: TimerOff24Regular },
        { label: 'Lead Not Interested', value: data.value?.not_interested_leads ?? 0, icon: NotInterestedRound },
        { label: 'Lead Not Qualified', value: data.value?.not_qualified_leads ?? 0, icon: Close },

        { label: 'Total Application', value: data.value?.applications ?? 0, icon: Application },
        { label: 'Application In Progress', value: data.value?.in_progress_applications ?? 0, icon: InProgress },
        { label: 'Application Approved', value: data.value?.approved_applications ?? 0, icon: MdCheckmark },
        { label: 'Application Rejected', value: data.value?.rejected_applications ?? 0, icon: Close },

        { label: 'Total Counselor', value: data.value?.counselors ?? 0, icon: ManageAccountsRound },
    ]
})
</script>
