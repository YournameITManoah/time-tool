<template>
    <router-link :href="href" :class="`link text-${color || 'primary'}`">
        {{ label }}
    </router-link>
</template>
<script setup lang="ts">
defineProps<{
    href: string
    label: string
    color?: string
}>()
</script>
