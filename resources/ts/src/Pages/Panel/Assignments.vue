<template>

    <Head title="Assignments" />

    <n-modal v-model:show="dialog" :title="!selectedId ? 'Add Assignment' : 'Edit Assignment'" preset="card"
        class="w-4/5 lg:w-1/3">
        <LeadAssignmentForm :closer="closeHandler" :payload="selectedId" :refetch="refetch" />
    </n-modal>

    <n-card>
        <n-flex justify="space-between" align="center" class="mb-5">
            <p class="text-xl font-semibold">Assignments</p>
            <n-button type="info" @click="dialog = !dialog">Add Assignment</n-button>
        </n-flex>

        <Table :headers="headers" :isLoading="isFetching" :found="isSuccess && data?.data?.data.length"
            :isError="isError" :error="error?.message">
            <template #rows>
                <tr v-for="(item, i) in data?.data?.data" :key="i">
                    <td>
                        <n-flex align="center">
                            <n-avatar :src="Images.logo" size="large" class="h-auto" />
                            <n-flex vertical :size="1">
                                <p class="text-base font-semibold">{{ item?.lead?.name ?? 'N/A' }}</p>
                                <p class="text-xs">{{ item?.lead?.email ?? 'N/A' }}</p>
                            </n-flex>
                        </n-flex>
                    </td>
                    <td>
                        <n-flex align="center">
                            <n-avatar :src="Images.logo" size="large" />
                            <n-flex vertical :size="1">
                                <p class="text-base font-semibold">{{ item?.counselor?.name ?? 'N/A' }}</p>
                                <p class="text-xs">{{ item?.counselor?.email ?? 'N/A' }}</p>
                            </n-flex>
                        </n-flex>
                    </td>
                    <td class="capitalize">{{ item.status ?? 'N/A' }}</td>
                    <td>
                        <n-space align="center">
                            <n-tooltip>
                                <template #trigger>
                                    <n-button strong secondary circle type="warning" @click="() => editHandler(item.id)"
                                        :loading="docDeletePending">
                                        <template #icon>
                                            <n-icon>
                                                <Edit16Filled />
                                            </n-icon>
                                        </template>
                                    </n-button>
                                </template>
                                Edit
                            </n-tooltip>
                            <n-tooltip>
                                <template #trigger>
                                    <n-button strong secondary circle type="error" :loading="docDeletePending"
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
                <Paginate :page="params.page" :offset="params.offset" :total="data?.data?.last_page"
                    :changeHandler="paramsChangeHandler" />
            </template>
        </Table>
    </n-card>
</template>

<script setup lang="ts">
import LeadAssignmentForm from '@/Components/Form/LeadAssignmentForm.vue';
import Paginate from '@/Components/UI/Paginate.vue';
import Table from '@/Components/UI/Table.vue';
import { Images } from '@/Constants/theme';
import Panel from '@/Layouts/Panel.vue';
import { deleteLeadAssignment, fetchLeadAssignments } from '@/States/Actions/LeadAssignments';
import { Head } from '@inertiajs/vue3';
import { useMutation, useQuery } from '@tanstack/vue-query';
import { Delete20Regular, Edit16Filled } from '@vicons/fluent';
import { useMessage } from 'naive-ui';
import { ref } from 'vue';

defineOptions({ layout: Panel })

const message = useMessage()
const headers: { label: string, align: 'left' | 'right' | 'justify' }[] = [
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
    queryKey: ['leads', params],
    queryFn: () => fetchLeadAssignments(`page=${params.value.page}&offset=${params.value.offset}&relations[]=counselor:id,name,email&relations[]=lead:id,name,email`),
})


const dialog = ref(false)
const selectedId = ref<string | null>(null)

const editHandler = (id: string) => {
    selectedId.value = id;
    dialog.value = true
}

const closeHandler = () => {
    if (selectedId.value) {
        selectedId.value = null;
    }
    dialog.value = false;
}

const { isPending: docDeletePending, mutate: docDeleteMutate } = useMutation({
    mutationFn: (id: string) => deleteLeadAssignment(id), onSuccess: (data) => {
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
