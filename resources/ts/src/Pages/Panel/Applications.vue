<template>
    <Panel>

        <Head title="Applications" />

        <n-card>
            <n-space justify="space-between" align="center">
                <p class="text-xl font-semibold mb-5">Applications</p>

                <n-flex justify="end">
                    <n-button :type="view === 'kanban' ? 'primary' : 'default'"
                        @click="view = 'kanban'">Kanban</n-button>
                    <n-button :type="view === 'table' ? 'primary' : 'default'" @click="view = 'table'">Table</n-button>
                </n-flex>
            </n-space>

            <template v-if="view === 'table'">
                <Table :error="error?.message" :found="isSuccess && data?.data?.data.length > 0" :headers="headers"
                    :isError="isError" :isLoading="isFetching">
                    <template #rows>
                        <tr v-for="(item, i) in data?.data?.data" :key="i">
                            <td>{{ item?.name ?? 'N/A' }}</td>
                            <td>
                                <n-flex align="center">
                                    <n-flex vertical :size="1">
                                        <p class="text-base font-semibold">{{ item?.lead?.name ?? 'N/A' }}</p>
                                        <p class="text-xs">{{ item?.lead?.email ?? 'N/A' }}</p>
                                    </n-flex>
                                </n-flex>
                            </td>
                            <td>
                                <n-flex align="center">
                                    <n-flex vertical :size="1">
                                        <p class="text-base font-semibold">{{ item?.counselor?.name ?? 'N/A' }}</p>
                                        <p class="text-xs">{{ item?.counselor?.email ?? 'N/A' }}</p>
                                    </n-flex>
                                </n-flex>
                            </td>
                            <td>{{ item?.status ?? 'N/A' }}</td>
                            <td>
                                <n-space align="center">
                                    <n-tooltip>
                                        <template #trigger>
                                            <n-button :loading="docDeletePending" circle secondary strong type="error"
                                                :disabled="currentUser?.role !== 'ADMIN'"
                                                @click="() => deleteHandler(item.id)">
                                                <template #icon>
                                                    <n-icon>
                                                        <Delete20Regular />
                                                    </n-icon>
                                                </template>
                                            </n-button>
                                        </template>
                                        Delete
                                    </n-tooltip>
                                </n-space>
                            </td>
                        </tr>
                    </template>
                    <template #paginate>
                        <Paginate :changeHandler="paramsChangeHandler" :offset="params.offset" :page="params.page"
                            :total="data?.data?.last_page" />
                    </template>
                </Table>
            </template>

            <template v-if="view === 'kanban'">
                <ApplicationKanbanBoard :isLoading="isFetching" :found="isSuccess && data?.data?.data.length > 0"
                    :data="data?.data?.data" :refetch="refetch" />
            </template>
        </n-card>
    </Panel>
</template>

<script setup lang="ts">
import Paginate from '@/Components/UI/Paginate.vue';
import Table from '@/Components/UI/Table.vue';
import Panel from '@/Layouts/Panel.vue';
import { Head } from '@inertiajs/vue3';
import { Delete20Regular } from '@vicons/fluent';
import { ref, computed } from 'vue';
import { fetchApplications, deleteApplication } from '@/States/Actions/Applications';
import { useQuery, useMutation } from '@tanstack/vue-query';
import { useMessage } from 'naive-ui';
import ApplicationKanbanBoard from '@/Components/ApplicationKanbanBoard.vue';
import useAuthStore from '@/States/Stores/authStore';

const message = useMessage()
const authStore = useAuthStore();

const view = ref('kanban')
const currentUser = computed(() => authStore.currentUser);

const headers: { label: string, align: 'left' | 'right' | 'justify' }[] = [
    { label: 'Name', align: 'left' },
    { label: 'Lead', align: 'left' },
    { label: 'Counselor', align: 'left' },
    { label: 'Status', align: 'left' },
    { label: 'Actions', align: 'left' },
]
const params = ref<{ [key: string]: number | null }>({
    page: 1,
    offset: 10,
})

const paramsChangeHandler = (field: string, value: number | null) => {
    params.value[field] = value;
}

const { isSuccess, isFetching, isError, data, error, refetch } = useQuery({
    queryKey: ['applications', params.value],
    queryFn: () => fetchApplications(`page=${params.value.page}&offset=${params.value.offset}&relations[]=lead:id,name,email&relations[]=counselor:id,name,email`),
})

const { isPending: docDeletePending, mutate: docDeleteMutate } = useMutation({
    mutationFn: (id: string) => deleteApplication(id), onSuccess: (data) => {
        message.success(data.message)
        refetch()
    },
    onError: (error) => {
        message.error(error.message)
    }
})

const deleteHandler = (id: string) => {
    docDeleteMutate(id)
}
</script>
