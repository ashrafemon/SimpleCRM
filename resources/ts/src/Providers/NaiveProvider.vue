<template>
    <n-config-provider>
        <n-global-style />
        <slot />
    </n-config-provider>
</template>

<script setup lang="ts">
import { NConfigProvider, NGlobalStyle } from 'naive-ui';
</script>
