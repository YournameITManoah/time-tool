<script lang="ts" setup>
defineOptions({ name: 'BreadcrumbsComponent' })

interface BreadcrumbItem {
    title: string
    href: string
    disabled?: boolean
}

defineProps<{
    items: BreadcrumbItem[]
}>()
</script>

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
