<template>
    <n-pagination v-model:page="props.page" v-model:page-size="props.offset" :page-count="props.total ?? 0"
        show-size-picker :page-sizes="sizes" :on-update:page="pageChanger" :on-update:page-size="pageSizeChanger" />
</template>

<script setup lang="ts">
const sizes = [10, 20, 50, 100, 500]
const props = defineProps<{
    page: number | null,
    offset: number | null,
    total: number | null | undefined,
    changeHandler?: (field: string, value: number | null) => void
}>()

const pageChanger = (value: number) => {
    if (!props.changeHandler) {
        return;
    }
    props.changeHandler('page', value)

}
const pageSizeChanger = (value: number) => {
    if (!props.changeHandler) {
        return;
    }
    props.changeHandler('offset', value)
}
</script>
