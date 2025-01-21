<template>
    <v-list nav>
        <template v-for="item in navigation" :key="item.href">
            <v-list-item
                v-if="item.external"
                :prepend-icon="item.icon"
                append-icon="mdi-open-in-new"
                :title="item.title"
                :href="item.href"
                link
            />
            <router-link
                v-else
                :href="item.href"
                :aria-current="item.current ? 'page' : false"
                @click="emit('close')"
            >
                <v-list-item
                    :class="{
                        'v-list-item--active': item.current,
                    }"
                    :prepend-icon="item.icon"
                    :title="item.title"
                    link
                />
            </router-link>
        </template>
    </v-list>
</template>
<script lang="ts" setup>
interface NavItem {
    current?: boolean
    external?: boolean
    href: string
    icon: string
    title: string
}

const { t } = useI18n()

const can = useProperty<Partial<Record<string, boolean>>>('auth.can')

const emit = defineEmits<{
    (e: 'close'): void
}>()

const navigation = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            current: router.matches('dashboard'),
            href: route('dashboard'),
            icon: 'mdi-view-dashboard',
            title: t('Dashboard'),
        },
        {
            current: router.matches('time-log.index'),
            href: route('time-log.index'),
            icon: 'mdi-clock-outline',
            title: t('Time Logs'),
        },
    ]

    if (can.value?.['view-admin']) {
        items.push({
            external: true,
            href: '/admin',
            icon: 'mdi-security',
            title: t('Admin Panel'),
        })
    }

    return items
})
</script>
