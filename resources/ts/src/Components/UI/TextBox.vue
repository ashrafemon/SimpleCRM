<template>
    <n-flex vertical>
        <p v-if="label" class="text-base font-medium">{{ label }}</p>
        <n-input v-bind="inputProps">
            <template #prefix>
                <slot name="prefix"></slot>
            </template>
            <template #suffix>
                <slot name="suffix"></slot>
            </template>
        </n-input>
    </n-flex>
</template>

<script setup lang="ts">
import { inputProps as nInputProps } from 'naive-ui';
import { computed } from 'vue';

const props = defineProps({
    ...nInputProps, label: { type: String, default: undefined }
});

const inputProps = computed(() => {
    const { label, ...rest } = props;
    return rest;
});
</script>
