<template>
    <template v-if="isLoading">
        <n-skeleton text :repeat="12" />
    </template>
    <template v-else-if="found">
        <ejs-kanban id="kanban" keyField="status" :dataSource="kanbanData" :cardSettings="cardSettings"
            @dragStop="onDragStop">
            <e-columns>
                <e-column headerText="In Progress" keyField="IN_PROGRESS"></e-column>
                <e-column headerText="Approved" keyField="APPROVED"></e-column>
                <e-column headerText="Rejected" keyField="REJECTED"></e-column>
            </e-columns>
        </ejs-kanban>
    </template>
    <template v-else>
        <n-empty description="No data found" />
    </template>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useMutation } from '@tanstack/vue-query';
import { useMessage } from 'naive-ui';
import { updateApplication } from '@/States/Actions/Applications';

const message = useMessage()

const { data, isLoading, found, refetch } = defineProps({
    data: {
        required: true,
        type: Array as PropType<{ id: string, name: string, status: string }[]>,
        default: []
    },
    isLoading: {
        required: true,
        type: Boolean,
        default: true
    },
    found: {
        required: true,
        type: Boolean,
        default: false
    },
    refetch: {
        required: false,
        type: Function,
        default: () => { }
    }
})

const cardSettings = ref<any>({
    contentField: "name",
    headerField: "lead",
})

const kanbanData = computed(() => {
    return data.map((item) => ({
        id: item.id,
        name: item.name,
        status: item.status,
        lead: item.lead.name,
    }))
})

const { mutate: updateStatus } = useMutation({
    mutationFn: (data: { id: string, status: string }) => updateApplication(data.id, data),
    onSuccess: (data) => {
        message.success(data.message)
        refetch()
    },
    onError: (error) => {
        message.error(error.message)
    }
})

const onDragStop = (args: any) => {
    if (args.data && args.data.length) {
        const draggedItem = args.data[0];
        updateStatus({ id: draggedItem.id, status: draggedItem.status })
    }
}

</script>

<style scoped></style>
