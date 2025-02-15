<template>
    <n-scrollbar y-placement="left">
        <n-table :single-line="false" class="mb-5">
            <thead v-if="props?.headers?.length">
                <tr>
                    <th v-for="(header, i) in props.headers" :key="i" :align="header.align ?? 'left'"
                        class="!font-semibold">
                        {{ header.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="props.isLoading">
                    <tr>
                        <td :colspan="props.headers?.length">
                            <n-skeleton text :repeat="12" />
                        </td>
                    </tr>

                </template>

                <template v-else-if="props.isError">
                    <tr>
                        <td :colspan="props.headers?.length">
                            <div class="py-10 px-1">
                                <n-empty description="Something went wrong" />
                            </div>
                        </td>
                    </tr>
                </template>

                <template v-else-if="!props.found">
                    <tr>
                        <td :colspan="props.headers?.length">
                            <div class="py-10 px-1">
                                <n-empty description="No data found..." />
                            </div>
                        </td>
                    </tr>
                </template>

                <template v-else>
                    <slot name="rows" />
                </template>
            </tbody>
        </n-table>
        <slot name="paginate" />
    </n-scrollbar>
</template>

<script setup lang="ts">
type Header = {
    label: string;
    align?: "left" | "center" | "right" | "justify" | "char" | undefined;
    width?: string;
}

const props = defineProps<{
    headers?: Header[];
    found?: boolean;
    isLoading?: boolean;
    isError?: boolean;
    error?: any
}>();
</script>
