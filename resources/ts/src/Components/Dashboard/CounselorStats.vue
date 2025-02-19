<template>
    <n-card>
        <n-flex justify="space-between" align="center" class="mb-5">
            <p class="text-xl font-semibold">Counselor Stats</p>
        </n-flex>

        <Table :headers="headers" :isLoading="isFetching" :found="isSuccess && data?.data?.stats.length"
            :isError="isError" :error="error?.message">
            <template #rows>
                <tr v-for="(item, i) in data?.data?.stats" :key="i">
                    <td>{{ item?.name ?? 'N/A' }}</td>
                    <td>{{ item?.total_leads ?? 0 }}</td>
                    <td>{{ item?.completed_leads ?? 0 }}</td>
                    <td>{{ item?.conversion_rate ?? 0 }}</td>
                </tr>
            </template>
        </Table>
    </n-card>
</template>

<script setup lang="ts">
import { fetchCounselorStatsReports } from '@/States/Actions/Reports';
import { useQuery } from '@tanstack/vue-query';
import Table from '../UI/Table.vue';

const headers: { label: string, align: 'left' | 'right' | 'justify' }[] = [
    { label: 'Name', align: 'left' },
    { label: 'Total Leads', align: 'left' },
    { label: 'Converted Leads', align: 'left' },
    { label: 'Conversion Rate', align: 'left' },
]

const { data, isFetching, isSuccess, isError, error } = useQuery({
    queryKey: ['counselor-stats'],
    queryFn: () => fetchCounselorStatsReports(),
})
</script>
