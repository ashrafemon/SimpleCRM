<template>
    <template v-if="props.isLoading">
        <n-skeleton text :repeat="12" />
    </template>

    <template v-else-if="props.isError">
        <n-empty :description="props.error" />
    </template>
</template>

<script setup lang="ts">
const props = defineProps({
    isLoading: {
        type: Boolean,
        default: true
    },
    isError: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
    }
})
</script>
