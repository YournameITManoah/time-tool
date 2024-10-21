<script lang="ts" setup>
interface NavItem {
    title: string
    icon: string
    href: string
    exact?: boolean
    external?: boolean
}

const can = useProperty('auth.can')

const navigation = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            icon: 'mdi-view-dashboard',
            href: route('dashboard'),
        },
        {
            title: 'Time Logs',
            icon: 'mdi-clock-outline',
            href: route('time-log.index'),
        },
    ]

    if (can.value?.['view-admin']) {
        items.push({
            title: 'Admin Panel',
            icon: 'mdi-security',
            href: '/admin',
            external: true,
        })
    }

    return items
})
</script>

<template>
    <v-list nav>
        <template v-for="item in navigation" :key="item.href">
            <v-list-item
                v-if="item.external"
                :prepend-icon="item.icon"
                :title="item.title"
                :href="item.href"
                link
            />
            <router-link v-else :href="item.href" as="div">
                <v-list-item
                    :class="{
                        'v-list-item--active': false,
                    }"
                    :exact="item.exact"
                    :prepend-icon="item.icon"
                    :title="item.title"
                    link
                />
            </router-link>
        </template>
    </v-list>
</template>
