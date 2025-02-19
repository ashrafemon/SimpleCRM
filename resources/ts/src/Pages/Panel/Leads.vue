<template>

    <Head title="Leads" />

    <n-modal v-model:show="dialog" :title="!selectedId ? 'Add Lead' : 'Edit Lead'" class="w-4/5 lg:w-1/3" preset="card">
        <LeadForm :closer="closeHandler" :payload="selectedId" :refetch="refetch" />
    </n-modal>

    <n-modal v-model:show="assignmentDialog" class="w-4/5 lg:w-1/3" preset="card" title="Add Assignment">
        <LeadAssignmentForm :closer="assignCloseHandler" :leadId="selectedLeadId" :payload="selectedId"
            :refetch="refetch" :userId="currentUser?.role === 'COUNSELOR' ? currentUser?.id : null" />
    </n-modal>

    <n-card>
        <n-flex align="center" class="mb-5" justify="space-between">
            <p class="text-xl font-semibold">Leads</p>
            <template v-if="currentUser?.role === 'ADMIN'">
                <n-button type="info" @click="dialog = !dialog">Add Lead</n-button>
            </template>
        </n-flex>

        <Table :error="error?.message" :found="isSuccess && data?.data?.data.length > 0" :headers="headers"
            :isError="isError" :isLoading="isFetching">
            <template #rows>
                <tr v-for="(item, i) in data?.data?.data" :key="i">
                    <td>{{ item?.name ?? 'N/A' }}</td>
                    <td>{{ item?.email ?? 'N/A' }}</td>
                    <td>{{ item?.phone ?? 'N/A' }}</td>
                    <td>{{ item?.maintainer?.counselor?.name ?? 'Not Assigned' }}</td>
                    <td class="capitalize">{{ item?.maintainer?.status ?? 'Not Assigned' }}</td>
                    <td>
                        <n-space align="center">
                            <n-tooltip>
                                <template #trigger>
                                    <n-button :loading="docDeletePending" circle secondary strong type="info"
                                        :disabled="item?.maintainer?.status === 'CONVERTED'"
                                        @click="() => currentUser?.role === 'COUNSELOR' ? updateAssignmentHandler(item.maintainer) : assignHandler(item.id)">
                                        <template #icon>
                                            <n-icon>
                                                <ConvertRange20Regular />
                                            </n-icon>
                                        </template>
                                    </n-button>
                                </template>
                                Assignment
                            </n-tooltip>
                            <template v-if="currentUser?.role === 'ADMIN'">
                                <n-tooltip>
                                    <template #trigger>
                                        <n-button :loading="docDeletePending" circle secondary strong type="warning"
                                            @click="() => editHandler(item.id)">
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
                                        <n-button :loading="docDeletePending" circle secondary strong type="error"
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
                            </template>
                        </n-space>
                    </td>
                </tr>
            </template>
            <template #paginate>
                <Paginate :changeHandler="paramsChangeHandler" :offset="params.offset" :page="params.page"
                    :total="data?.data?.last_page" />
            </template>
        </Table>
    </n-card>
</template>

<script lang="ts" setup>
import LeadAssignmentForm from '@/Components/Form/LeadAssignmentForm.vue';
import LeadForm from '@/Components/Form/LeadForm.vue';
import Paginate from '@/Components/UI/Paginate.vue';
import Table from '@/Components/UI/Table.vue';
import Panel from '@/Layouts/Panel.vue';
import { deleteLead, fetchLeads } from '@/States/Actions/Leads';
import useAuthStore from '@/States/Stores/authStore';
import { Head } from '@inertiajs/vue3';
import { useMutation, useQuery } from '@tanstack/vue-query';
import { ConvertRange20Regular, Delete20Regular, Edit16Filled } from '@vicons/fluent';
import { useMessage } from 'naive-ui';
import { computed, ref } from 'vue';

defineOptions({ layout: Panel })

const message = useMessage()
const authStore = useAuthStore();

const currentUser = computed(() => authStore.currentUser);

const headers: { label: string, align: 'left' | 'right' | 'justify' }[] = [
    { label: 'Name', align: 'left' },
    { label: 'Email', align: 'left' },
    { label: 'Phone', align: 'left' },
    { label: 'Maintainer', align: 'left' },
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
    queryFn: () => fetchLeads(`page=${params.value.page}&offset=${params.value.offset}&relations[]=maintainer:id,lead_id,user_id,status&relations[]=maintainer.counselor:id,name,email`),
})

const dialog = ref(false)
const assignmentDialog = ref(false)
const selectedId = ref<string | null>(null)
const selectedLeadId = ref<string | null>(null)

const assignHandler = (leadId: string) => {
    selectedLeadId.value = leadId;
    assignmentDialog.value = true
}
const assignCloseHandler = () => {
    if (selectedLeadId.value) {
        selectedLeadId.value = null;
    }
    assignmentDialog.value = false;
}

const updateAssignmentHandler = (item: { id: string, lead_id: string }) => {
    console.log({ item })
    selectedLeadId.value = item.lead_id;
    selectedId.value = item.id;
    assignmentDialog.value = true
}

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
    mutationFn: (id: string) => deleteLead(id), onSuccess: (data) => {
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
