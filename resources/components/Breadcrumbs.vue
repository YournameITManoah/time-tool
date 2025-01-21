<template>
    <nav :aria-label="t('Breadcrumbs')">
        <v-breadcrumbs :items="items" tag="ol">
            <template #item="{ item }">
                <li
                    class="v-breadcrumbs-item"
                    :class="{
                        'v-breadcrumbs-item--disabled': item.current,
                    }"
                >
                    <router-link
                        :href="item.href"
                        :aria-current="item.current ? 'page' : false"
                        class="v-breadcrumbs-item--link"
                    >
                        {{ item.title }}
                    </router-link>
                </li>
            </template>
        </v-breadcrumbs>
    </nav>
</template>
<script lang="ts" setup>
defineOptions({ name: 'BreadcrumbsComponent' })

const { t } = useI18n()

interface BreadcrumbItem {
    current?: boolean
    href: string
    title: string
}

defineProps<{
    items: BreadcrumbItem[]
}>()
</script>
