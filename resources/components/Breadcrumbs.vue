<template>
    <v-breadcrumbs :items="items">
        <template #item="{ item }">
            <router-link
                v-if="!item.disabled"
                :href="item.href"
                class="v-breadcrumbs-item--link"
            >
                {{ item.title }}
            </router-link>
            <span v-else>{{ item.title }}</span>
        </template>
    </v-breadcrumbs>
</template>
<script lang="ts" setup>
defineOptions({ name: 'BreadcrumbsComponent' })

interface BreadcrumbItem {
    disabled?: boolean
    href: string
    title: string
}

defineProps<{
    items: BreadcrumbItem[]
}>()
</script>
