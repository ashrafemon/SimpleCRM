<template>

    <Head title="Applications" />

    <n-card>
        <n-space justify="space-between" align="center">
            <p class="text-xl font-semibold mb-5">Applications</p>
        </n-space>

        <Table :headers="headers" :found="true">
            <template #rows>
                <tr v-for="(v, i) in 10" :key="i">
                    <td>25/09/2004</td>
                    <td>
                        <n-flex align="center">
                            <n-avatar :src="Images.logo" size="large" class="h-auto" />
                            <n-flex vertical :size="1">
                                <p class="text-base font-semibold">Ashraf Emon</p>
                                <p class="text-xs">ashraf.emon143@gmail.com</p>
                            </n-flex>
                        </n-flex>
                    </td>
                    <td>
                        <n-flex align="center">
                            <n-avatar :src="Images.logo" size="large" />
                            <n-flex vertical :size="1">
                                <p class="text-base font-semibold">Ashraf Emon</p>
                                <p class="text-xs">ashraf.emon143@gmail.com</p>
                            </n-flex>
                        </n-flex>
                    </td>
                    <td>
                        <n-tag type="success">In Progress</n-tag>
                    </td>
                    <td>
                        <n-space align="center">
                            <n-tooltip>
                                <template #trigger>
                                    <n-button strong secondary circle type="primary">
                                        <template #icon>
                                            <n-icon>
                                                <View />
                                            </n-icon>
                                        </template>
                                    </n-button>
                                </template>
                                View
                            </n-tooltip>
                            <n-tooltip>
                                <template #trigger>
                                    <n-button strong secondary circle type="warning">
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
                                    <n-button strong secondary circle type="error">
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
                <Paginate :page="params.page" :offset="params.offset" :total="2" :changeHandler="paramsChangeHandler" />
            </template>
        </Table>
    </n-card>
</template>

<script setup lang="ts">
import Paginate from '@/Components/UI/Paginate.vue';
import Table from '@/Components/UI/Table.vue';
import { Images } from '@/Constants/theme';
import Panel from '@/Layouts/Panel.vue';
import { Head } from '@inertiajs/vue3';
import { View } from '@vicons/carbon';
import { Delete20Regular, Edit16Filled } from '@vicons/fluent';
import { ref } from 'vue';

defineOptions({ layout: Panel })

const headers: { label: string, align: 'left' | 'right' | 'justify' }[] = [
    { label: 'Date', align: 'left' },
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
</script>
